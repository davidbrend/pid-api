<?php

namespace App\Facades;

use _PHPStan_93af41bf5\Nette\Utils\DateTime;
use App\Api\V1\PointsOfSale\Models\Response\PointOfSaleResponse;
use App\Base\Database\EntityManagerDecorator;
use App\Models\OpeningHours;
use App\Models\PayMethod;
use App\Models\PointOfSale;
use App\Models\PointType;
use App\Models\Service;
use App\Models\ServiceGroup;
use App\Services\PIDService;
use GuzzleHttp\Exception\GuzzleException;

class PidFacade
{
    public function __construct(protected PIDService $service, protected EntityManagerDecorator $em)
    {
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws \Exception
     */
    public function synchronizePointsOfSaleFromPID(): void
    {
        $pointsOfSaleJson = $this->service->getPointsOfSaleJson();
        $constsJson = $this->service->getConstsJson()['pointsOfSaleConsts'] ?? [];

        if (count($pointsOfSaleJson) === 0 || count($constsJson) === 0) {
            throw new \Exception('Invalid data to parse');
        }

        foreach ($pointsOfSaleJson as $i => $data) {
            $entity = $this->em->getRepository(PointOfSale::class)->findOneBy(['id' => $data['id']]);
            if ($entity === null) {
                $entity = new PointOfSale();
                $this->createPointOfSale($entity, $data, $constsJson);
                $this->em->persist($entity);
            } else {
                $this->createPointOfSale($entity, $data, $constsJson);
            }

            $this->em->flush();
        }
    }

    /**
     * @param PointOfSale $pointOfSale
     * @param array<mixed> $pointsOfSaleJson
     * @param array<mixed> $constsJson
     * @return void
     * @throws \Exception
     */
    private function createPointOfSale(PointOfSale $pointOfSale, array $pointsOfSaleJson, array $constsJson): void
    {
        $properties = ['id', 'name', 'address', 'lat', 'lon'];
        foreach ($properties as $property) {
            if (isset($pointsOfSaleJson[$property])) {
                $pointOfSale->{'set' . ucfirst($property)}($pointsOfSaleJson[$property]);
            }
        }

        $openingHoursArr = $pointsOfSaleJson['openingHours'];
        if (isset($openingHoursArr)) {
            $pointOfSale->setOpeningHours($this->getOpeningHours($openingHoursArr, $pointOfSale));
        }

        [$pointTypes, $serviceGroups, $payMethodsArr] = $this->getParsedDataFromConstsJson($constsJson, $pointOfSale);

        $type = $pointsOfSaleJson['type'];
        if (isset($type)) {
            foreach ($pointTypes as $pointType) {
                if ($pointType->getName() === $type) {
                    $pointOfSale->setType($pointType);
                }
            }
        }

        $services = $pointsOfSaleJson['services'];
        if (isset($services)) {
            $filteredServiceGroups = $this->getAllServiceGroupsWithFilteredServicesFromArrByDecimal($services, $serviceGroups);
            $pointOfSale->setServiceGroups($filteredServiceGroups);
        }

        $payMethods = $pointsOfSaleJson['payMethods'];
        if (isset($payMethods)) {
            $filteredPayMethods = $this->getAllPayMethodsFromArrByDecimal($payMethods, $payMethodsArr);
            $pointOfSale->setPayMethods($filteredPayMethods);
        }
    }

    /**
     * @param array<mixed> $constsJson
     * @return array{PointType[], ServiceGroup[], PayMethod[]}
     */
    private function getParsedDataFromConstsJson(array $constsJson, PointOfSale $pointOfSale): array
    {
        $pointTypes = [];
        $serviceGroups = [];
        $payMethods = [];
        foreach ($constsJson as $part) {
            if (array_key_exists('pointTypes', $part)) {
                $pointTypes = $this->getPointTypesFromPart($part['pointTypes'], $pointOfSale);
            }

            if (array_key_exists('serviceGroups', $part)) {
                $serviceGroups = $this->getServiceGroupsFromPart($part['serviceGroups'], $pointOfSale);
            }
            if (array_key_exists('payMethods', $part)) {
                $payMethods = $this->getPayMethodsFromPart($part['payMethods'], $pointOfSale);

            }
        }

        return [$pointTypes, $serviceGroups, $payMethods];
    }

    /**
     * @param array<mixed> $payMethods
     * @return array<PayMethod>
     */
    private function getPayMethodsFromPart(array $payMethods, PointOfSale $pointOfSale): array
    {
        $res = [];
        foreach ($payMethods as $payMethod) {
            $entity = new PayMethod();

            $entity->setArr($payMethod);
            $entity->addPointOfSale($pointOfSale);

            $res[] = $entity;
        }

        return $res;
    }

    /**
     * @param array<mixed> $serviceGroups
     * @return array<ServiceGroup>
     */
    private function getServiceGroupsFromPart(array $serviceGroups, PointOfSale $pointOfSale): array
    {
        $res = [];
        foreach ($serviceGroups as $serviceGroup) {
            $entity = new ServiceGroup();
            $entity->setDesc($serviceGroup['desc']);
            $entity->addPointOfSale($pointOfSale);
            $services = $this->getServicesFromServiceGroupsPart($serviceGroup['services'], $entity);
            $entity->setServices($services);
            $res[] = $entity;
        }

        return $res;
    }

    /**
     * @param array<mixed> $pointTypes
     * @return array<PointType>
     */
    private function getPointTypesFromPart(array $pointTypes, PointOfSale $pointOfSale): array
    {
        $res = [];
        foreach ($pointTypes as $pointType) {
            $entity = new PointType();
            $entity->setArr($pointType);
            $entity->addPointOfSale($pointOfSale);

            $res[] = $entity;
        }
        return $res;
    }

    /**
     * @param array<mixed> $services
     * @param ServiceGroup $serviceGroup
     * @return array<Service>
     */
    private function getServicesFromServiceGroupsPart(array $services, ServiceGroup $serviceGroup): array
    {
        $res = [];
        foreach ($services as $service) {
            $entity = new Service();
            $entity->setArr($service);
            $entity->addServiceGroup($serviceGroup);

            $res[] = $entity;
        }

        return $res;
    }

    /**
     * @param array<mixed> $openingHoursArr
     * @return array<OpeningHours>
     */
    private function getOpeningHours(array $openingHoursArr, PointOfSale $pointOfSale): array
    {
        $res = [];
        foreach ($openingHoursArr as $openingHour) {
            $entity = new OpeningHours();

            $entity->setArr($openingHour);
            $entity->addPointOfSale($pointOfSale);
            $res[] = $entity;
        }

        return $res;
    }

    /**
     * @param int $payMethodsDecimal
     * @param array<PayMethod> $payMethodsArr
     * @return array<PayMethod>
     */
    private function getAllPayMethodsFromArrByDecimal(int $payMethodsDecimal, array $payMethodsArr): array
    {
        $res = [];
        foreach ($payMethodsArr as $payMethod) {
            if (($payMethod->getVal() & $payMethodsDecimal) === $payMethod->getVal()) {
                $res[] = $payMethod;
            }
        }
        return $res;
    }

    /**
     * @param int $servicesDecimal
     * @param array<ServiceGroup> $serviceGroups
     * @return array<ServiceGroup>
     */
    private function getAllServiceGroupsWithFilteredServicesFromArrByDecimal(int $servicesDecimal, mixed $serviceGroups): array
    {
        $res = [];
        foreach ($serviceGroups as $serviceGroup) {
            $clonedServiceGroup = clone $serviceGroup;
            $filteredServices = $serviceGroup->getServices()->filter(
                function($service) use ($servicesDecimal) {
                    return ($service->getVal() & $servicesDecimal) === $service->getVal();
                }
            );

            $filteredServicesArray = $filteredServices->toArray();
            if (count($filteredServicesArray) > 0) {
                $clonedServiceGroup->setServices($filteredServicesArray);
                $res[] = $clonedServiceGroup;
            }
        }

        return $res;
    }

    /**
     * @return array<PointOfSaleResponse>
     */
    public function getAllPointsOfSaleByCriteria(?\DateTimeInterface $date = null, bool $isOpen = false): array
    {
        $queryBuilder = $this->em->getRepository(PointOfSale::class)->createQueryBuilder('pos')
            ->leftJoin('pos.openingHours', 'oh');

        $isActualDate = false;
        if ($date === null) {
            $date = new DateTime();
            $isActualDate = true;
        }

        $dayOfWeek = (int)$date->format('N') - 1;
        if ($isOpen) {
            $queryBuilder
                ->andWhere('oh.from <= :dayOfWeek AND oh.to >= :dayOfWeek')
                ->setParameter('dayOfWeek', $dayOfWeek);
        }

        $pointsOfSale = $queryBuilder->getQuery()->getResult();
        $time = $date->format('H:i');

        $result = [];
        foreach ($pointsOfSale as $pos) {
            if (!$isOpen && $isActualDate) {
                $result[] = PointOfSaleResponse::from($pos);
            } else {
                foreach ($pos->getOpeningHours() as $oh) {
                    $times = explode(',', $oh->getHours());
                    foreach ($times as $timeRange) {
                        $timeParts = preg_split("/[\-\–\—]/u", $timeRange);
                        if ($timeParts !== false) {
                            [$startTimeStr, $endTimeStr] = array_map('trim', $timeParts);

                            if (($time >= $startTimeStr && $endTimeStr >= $time) &&
                                $oh->getFrom() <= $dayOfWeek && $oh->getTo() >= $dayOfWeek) {
                                $result[$pos->getId()] ??= PointOfSaleResponse::from($pos);
                            }
                        }

                    }
                }
            }
        }
        return $result;
    }
}