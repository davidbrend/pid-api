<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\PointTypeRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: PointTypeRepository::class)]
#[Table(name: 'pointTypes')]
class PointType extends BaseEntity
{
    #[Column]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;
    #[Column(length: 255)]
    protected string $name;
    #[Column(name: "`description`", length: 255)]
    protected string $desc;
    #[OneToOne(inversedBy: 'pointType', targetEntity: PointOfSale::class)]
    #[JoinColumn(nullable: true)]
    protected ?PointOfSale $pointOfSale = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PointType
    {
        $this->name = $name;
        return $this;
    }

    public function getDesc(): string
    {
        return $this->desc;
    }

    public function setDesc(string $desc): PointType
    {
        $this->desc = $desc;
        return $this;
    }

    public function getPointOfSale(): ?PointOfSale
    {
        return $this->pointOfSale;
    }

    public function setPointOfSale(?PointOfSale $pointOfSale): PointType
    {
        $this->pointOfSale = $pointOfSale;
        return $this;
    }
}