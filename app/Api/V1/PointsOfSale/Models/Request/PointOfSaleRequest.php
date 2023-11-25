<?php

namespace App\Api\V1\PointsOfSale\Models\Request;

use Apitte\Core\Mapping\Request\BasicEntity;
use App\Api\V1\PointsOfSale\Models\Response\OpeningHoursResponse;
use App\Api\V1\PointsOfSale\Models\Response\PayMethodResponse;
use App\Api\V1\PointsOfSale\Models\Response\PointTypeResponse;
use App\Api\V1\PointsOfSale\Models\Response\ServiceGroupResponse;

final class PointOfSaleRequest extends BasicEntity
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
}