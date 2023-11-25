<?php

namespace App\Api\V1\PointsOfSale\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\OpeningHours;
use App\Models\PointOfSale;
use App\Models\PointType;

final class PointTypeResponse
{
    public string $name;
    public string $desc;

    public static function from(?PointType $pointType = null): self
    {
        if ($pointType === null) {
            return new self();
        }

        $self = new self();
        $self->name = $pointType->getName();
        $self->desc = $pointType->getDesc();

        return $self;
    }

}