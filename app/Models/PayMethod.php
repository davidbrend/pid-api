<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\PayMethodRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: PayMethodRepository::class)]
#[Table(name: 'payMethods')]
class PayMethod extends BaseEntity
{
    #[Column]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;
    #[Column]
    protected int $val;
    #[Column(type:'text')]
    protected string $desc;
    #[ManyToOne(targetEntity: PointOfSale::class, inversedBy: 'payMethods')]
    #[JoinColumn(nullable: true)]
    protected ?PointOfSale $pointOfSale = null;

    public function getVal(): int
    {
        return $this->val;
    }

    public function setVal(int $val): PayMethod
    {
        $this->val = $val;
        return $this;
    }

    public function getDesc(): string
    {
        return $this->desc;
    }

    public function setDesc(string $desc): PayMethod
    {
        $this->desc = $desc;
        return $this;
    }

    public function getPointOfSale(): ?PointOfSale
    {
        return $this->pointOfSale;
    }

    public function setPointOfSale(?PointOfSale $pointOfSale): PayMethod
    {
        $this->pointOfSale = $pointOfSale;
        return $this;
    }
}