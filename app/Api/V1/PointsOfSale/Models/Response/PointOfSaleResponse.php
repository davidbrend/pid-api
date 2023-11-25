<?php

namespace App\Api\V1\PointsOfSale\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\PointOfSale;
use App\Models\PointType;

final class PointOfSaleResponse extends BasicEntity
{
    public string $id;
    public string $name;
    public PointTypeResponse $pointType;
    /** @var OpeningHoursResponse[] $openingHours */
    public array $openingHours = [];
    public float $lat;
    public float $lon;
    /** @var PayMethodResponse[] $payMethods */
    public array $payMethods = [];

    /** @var ServiceGroupResponse[] $serviceGroups */
    public array $serviceGroups = [];

    public static function from(PointOfSale $pointOfSale): self
    {
        $self = new self();
        $self->id = $pointOfSale->getId();
        $self->name = $pointOfSale->getName();
        $self->pointType = PointTypeResponse::from($pointOfSale->getType());
        $self->lat = $pointOfSale->getLat();
        $self->lon = $pointOfSale->getLon();

        foreach ($pointOfSale->getOpeningHours()->getValues() as $oh) {
            $self->openingHours[] = OpeningHoursResponse::from($oh);
        }

        foreach ($pointOfSale->getPayMethods()->getValues() as $pm) {
            $self->payMethods[] = PayMethodResponse::from($pm);
        }

        foreach ($pointOfSale->getServiceGroups()->getValues() as $gs) {
            $self->serviceGroups[] = ServiceGroupResponse::from($gs);
        }

        return $self;
    }

}