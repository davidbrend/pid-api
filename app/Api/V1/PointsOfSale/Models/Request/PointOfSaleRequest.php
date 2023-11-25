<?php

namespace App\Api\V1\PointsOfSale\Models\Request;

use Apitte\Core\Mapping\Request\BasicEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

final class PointOfSaleRequest extends BasicEntity
{
    #[NotBlank]
    public string $id;
    #[NotBlank]
    public string $name;
    // #[NotBlank]
    // public array $openingHours; // I do not know how to include into swagger as Request entity body example
    #[NotBlank]
    public float $lat;
    #[NotBlank]
    public float $lon;
}