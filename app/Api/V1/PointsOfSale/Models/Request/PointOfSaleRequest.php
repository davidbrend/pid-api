<?php

namespace App\Api\V1\PointsOfSale\Models\Request;

use Apitte\Core\Mapping\Request\BasicEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

final class PointOfSaleRequest extends BasicEntity
{
    #[NotBlank]
    public string $name;
}