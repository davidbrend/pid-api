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
    public function getAllPointsOfSale(): array
    {
        return $this->em->getRepository(PointOfSale::class)->findAll();
    }

    /**
     * @param PointOfSale $pointOfSale
     * @param array<mixed> $pointsOfSaleJson
     * @param array<mixed> $constsJson
     * @return void
     */
    private function createPointOfSale(PointOfSale $pointOfSale, array $pointsOfSaleJson, array $constsJson): void
    {
        $properties = ['id', 'name', 'address', 'lat', 'lon'];
        foreach ($properties as $property) {
            if (isset($pointsOfSaleJson[$property])) {
                $pointOfSale->{'set'.ucfirst($property)}($pointsOfSaleJson[$property]);
            }
        }

        [$pointTypes, $serviceGroups, $payMethodsArr] = $this->getParsedDataFromConstsJson($constsJson);

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
            $pointOfSale->setOpeningHours($this->getOpeningHours($openingHoursArr));
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
    private function getParsedDataFromConstsJson(array $constsJson): array
    {
        $pointTypes = [];
        $serviceGroups = [];
        $payMethods = [];
        foreach ($constsJson as $part) {
            if (array_key_exists('pointTypes', $part)) {
                $pointTypes = $this->getPointTypesFromPart($part['pointTypes']);
            }

            if (array_key_exists('serviceGroups', $part)) {
                $serviceGroups = $this->getServiceGroupsFromPart($part['serviceGroups']);
            }

            if (array_key_exists('payMethods', $part)) {
                $payMethods = $this->getPayMethodsFromPart($part['payMethods']);

            }
        }

        return [$pointTypes, $serviceGroups, $payMethods];
    }

    /**
     * @param array<mixed> $payMethods
     * @return array<PayMethod>
     */
    private function getPayMethodsFromPart(array $payMethods): array
    {
        $res = [];
        foreach ($payMethods as $payMethod) {
            $entity = $this->em->getRepository(PayMethod::class)->findOneBy(['val' => $payMethod['val']]);
            if ($entity === null) {
                $entity = new PayMethod();
            }

            $entity->setArr($payMethod);
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
    private function getServiceGroupsFromPart(array $serviceGroups): array
    {
        $res = [];
        foreach ($serviceGroups as $serviceGroup) {
            $entity = $this->em->getRepository(ServiceGroup::class)
                ->findOneBy(['desc' => $serviceGroup['desc']]);

            if ($entity === null) {
                $entity = new ServiceGroup();
            }

            $entity->setDesc($serviceGroup['desc']);
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
    private function getPointTypesFromPart(array $pointTypes): array
    {
        $res = [];
        foreach ($pointTypes as $pointType) {
            $entity = $this->em->getRepository(PointType::class)
                ->findOneBy(['name' => $pointType['name']]);

            if ($entity === null) {
                $entity = new PointType();
            }
            $entity->setArr($pointType);
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
    private function getOpeningHours(array $openingHoursArr): array
    {
        $res = [];
        foreach ($openingHoursArr as $openingHour) {
            $entity = $this->em->getRepository(OpeningHours::class)
                ->findOneBy(['hours' => $openingHour['hours']]);

            if ($entity === null) {
                $entity = new OpeningHours();
            }

            $entity->setArr($openingHour);
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