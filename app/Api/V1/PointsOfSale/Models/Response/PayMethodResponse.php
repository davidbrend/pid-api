<?php

namespace App\Api\V1\PointsOfSale\Models\Response;

use Apitte\Core\Mapping\Response\BasicEntity;
use App\Models\PayMethod;
use App\Models\PointOfSale;
use App\Models\PointType;

final class PayMethodResponse extends BasicEntity
{
    public int $val;
    public string $desc;

    public static function from(PayMethod $payMethod): self
    {
        $self = new self();
        $self->val = $payMethod->getVal();
        $self->desc = $payMethod->getDesc();

        return $self;
    }

}