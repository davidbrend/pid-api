<?php

namespace App\Api\V1\PointsOfSale\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\PointOfSale;

final class PointOfSaleResponse extends BasicEntity
{
    public string $id;
    public string $name;

    public static function from(PointOfSale $pointOfSale): self
    {
        $self = new self();
        $self->id = $pointOfSale->getId();
        $self->name = $pointOfSale->getName();

        return $self;
    }

}