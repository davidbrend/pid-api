<?php

namespace App\Api\V1\PointsOfSale\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\OpeningHours;
use App\Models\PointOfSale;

final class OpeningHoursResponse
{
    public int $from;
    public int $to;
    public string $hours;

    public static function from(OpeningHours $openingHours): self
    {
        $self = new self();
        $self->from = $openingHours->getFrom();
        $self->to = $openingHours->getTo();
        $self->hours = $openingHours->getHours();

        return $self;
    }

}