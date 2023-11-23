<?php

namespace App\Facades;

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

        foreach ($pointsOfSaleJson as $data) {
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
     * @return array<PointOfSale>
     */
    public function getAllPointsOfSaleByCriteria(?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null, bool $isOpen = false, int $offset = 0, int $limit = 1000): array
    {
        $queryBuilder = $this->em->getRepository(PointOfSale::class)->createQueryBuilder('pos');
        $queryBuilder->select('pos')
            ->leftJoin('pos.openingHours', 'oh');

        if ($from !== null) {
            $queryBuilder->andWhere('oh.from <= :fromDay')
                ->setParameter('fromDay', $from->format('w'));
        }

        if ($to !== null) {
            $queryBuilder->andWhere('oh.to >= :toDay')
                ->setParameter('toDay', $to->format('w'));
        }

        if ($isOpen) {
            $currentDayOfWeek = (new \DateTime())->format('w');
            $queryBuilder->andWhere(':currentDayOfWeek BETWEEN oh.from AND oh.to')
                ->setParameter('currentDayOfWeek', $currentDayOfWeek);
        }

        $openPointsOfSaleArr = [];
        try {
            /** @var array<PointOfSale> $openPointsOfSale */
            $openPointsOfSale = $queryBuilder->getQuery()->getResult();
            foreach ($openPointsOfSale as $op) {
                /** @var OpeningHours $oh */
                foreach ($op->getOpeningHours()->getValues() as $oh) {
                    /*$times = explode(',', $oh->getHours());
                    foreach ($times as $timeRange) {
                        [$startTime, $endTime] = explode('-', $timeRange);
                        bdump($startTime);
                    }*/
                }
                $openPointsOfSaleArr[] = $op;
            }
        } catch (\Throwable $exception) {
            // Handle exceptions or return an empty array when no results found
        }

        return $openPointsOfSaleArr;
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
                $pointOfSale->{'set'.ucfirst($property)}($pointsOfSaleJson[$property]);
            }
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

        $openingHoursArr = $pointsOfSaleJson['openingHours'];
        if (isset($openingHoursArr)) {
            $pointOfSale->setOpeningHours($this->getOpeningHours($openingHoursArr, $pointOfSale));
        }

        $services = $pointsOfSaleJson['services'];
        if (isset($services)) {
            $filteredServiceGroups = $this->getAllServiceGroupsWithFilteredServicesFromArrByDecimal($services, $serviceGroups);
            $pointOfSale->setServices($filteredServiceGroups);
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
            $entity = $this->em->getRepository(PayMethod::class)->findOneBy(['val' => $payMethod['val']]);
            if ($entity === null) {
                $entity = new PayMethod();
            }

            $entity->setArr($payMethod);
            $entity->setPointOfSale($pointOfSale);
            $this->em->persist($entity);

            $res[] = $entity;
        }

        $this->em->flush();
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
            $entity = $this->em->getRepository(ServiceGroup::class)
                ->findOneBy(['desc' => $serviceGroup['desc']]);

            if ($entity === null) {
                $entity = new ServiceGroup();
            }

            $entity->setDesc($serviceGroup['desc']);
            $entity->setPointOfSale($pointOfSale);
            $this->em->persist($entity);

            $entity->setServices($this->getServicesFromServiceGroupsPart($serviceGroup['services'], $entity));
            $res[] = $entity;
        }

        $this->em->flush();
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
            $entity = $this->em->getRepository(PointType::class)
                ->findOneBy(['name' => $pointType['name']]);

            if ($entity === null) {
                $entity = new PointType();
            }
            $entity->setArr($pointType);
            $entity->setPointOfSale($pointOfSale);
            $this->em->persist($entity);

            $res[] = $entity;
        }

        $this->em->flush();
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
            $entity = $this->em->getRepository(Service::class)
                ->findOneBy(['val' => $service['val']]);

            if ($entity === null) {
                $entity = new Service();
            }
            $entity->setArr($service);
            $entity->setServiceGroup($serviceGroup);
            $this->em->persist($entity);

            $res[] = $entity;
        }

        $this->em->flush();
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
            $entity = $this->em->getRepository(OpeningHours::class)
                ->findOneBy(['hours' => $openingHour['hours']]);

            if ($entity === null) {
                $entity = new OpeningHours();
            }

            $entity->setArr($openingHour);
            $entity->setPointOfSale($pointOfSale);
            $this->em->persist($entity);
            $res[] = $entity;
        }

        $this->em->flush();
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
}