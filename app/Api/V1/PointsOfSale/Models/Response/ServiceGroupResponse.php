<?php

namespace App\Api\V1\PointsOfSale\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\PayMethod;
use App\Models\PointOfSale;
use App\Models\PointType;
use App\Models\Service;
use App\Models\ServiceGroup;

final class ServiceGroupResponse extends BasicEntity
{
    public string $desc;
    /** @var ServiceResponse[] $services */
    public array $services = [];

    public static function from(ServiceGroup $serviceGroup): self
    {
        $self = new self();
        $self->desc = $serviceGroup->getDesc();

        foreach ($serviceGroup->getServices()->getValues() as $service) {
            $self->services[] = ServiceResponse::from($service);
        }

        return $self;
    }

}