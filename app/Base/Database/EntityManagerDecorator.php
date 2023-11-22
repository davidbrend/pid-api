<?php

namespace App\Base\Database;

use App\Models\OpeningHours;
use App\Models\PayMethod;
use App\Models\PointOfSale;
use App\Models\PointType;
use App\Models\Service;
use App\Models\ServiceGroup;
use App\Repositories\OpeningHoursRepository;
use App\Repositories\PayMethodRepository;
use App\Repositories\PointOfSaleRepository;
use App\Repositories\PointTypeRepository;
use App\Repositories\ServiceGroupRepository;
use App\Repositories\ServiceRepository;
use Doctrine\ORM\Decorator\EntityManagerDecorator as DoctrineEntityManagerDecorator;

final class EntityManagerDecorator extends DoctrineEntityManagerDecorator
{
    public function getOpeningHoursRepository(): OpeningHoursRepository
    {
        /** @phpstan-ignore-next-line */
        return $this->getRepository(OpeningHours::class);
    }

    public function getPayMethodRepository(): PayMethodRepository
    {
        return $this->getRepository(PayMethod::class);
    }

    public function getPointOfSaleRepository(): PointOfSaleRepository
    {
        return $this->getRepository(PointOfSale::class);
    }

    public function getPointTypeRepository(): PointTypeRepository
    {
        return $this->getRepository(PointType::class);
    }

    public function getServiceGroupRepository(): ServiceGroupRepository
    {
        return $this->getRepository(ServiceGroup::class);
    }

    public function getServiceRepository(): ServiceRepository
    {
        return $this->getRepository(Service::class);
    }
}