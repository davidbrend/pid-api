<?php

namespace App\Api\V1\PointsOfSale\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\PayMethod;
use App\Models\PointOfSale;
use App\Models\PointType;
use App\Models\Service;
use App\Models\ServiceGroup;

final class ServiceResponse extends BasicEntity
{
    public int $val;
    public string $desc;

    public static function from(Service $service): self
    {
        $self = new self();
        $self->val = $service->getVal();
        $self->desc = $service->getDesc();

        return $self;
    }

}