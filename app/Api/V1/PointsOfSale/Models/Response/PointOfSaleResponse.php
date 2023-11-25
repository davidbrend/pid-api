<?php

namespace App\Api\V1\PointsOfSale\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\PointOfSale;

final class PointOfSaleResponse extends BasicEntity
{
    public string $id;
    public string $name;
    /** @var OpeningHoursResponse[] $openingHours */
    public array $openingHours;
    public float $lat;
    public float $lon;

    public static function from(PointOfSale $pointOfSale): self
    {
        $self = new self();
        $self->id = $pointOfSale->getId();
        $self->name = $pointOfSale->getName();
        $self->lat = $pointOfSale->getLat();
        $self->lon = $pointOfSale->getLon();
        foreach ($pointOfSale->getOpeningHours()->getValues() as $oh) {
            $self->openingHours[] = OpeningHoursResponse::from($oh);
        }

        return $self;
    }

}