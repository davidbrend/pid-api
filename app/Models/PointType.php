<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\PointTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
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
    #[OneToMany(mappedBy: 'pointTypes', targetEntity: PointOfSale::class, cascade: ['persist'])]
    #[JoinTable(name: 'point_of_sale_point_types')]
    protected Collection $pointsOfSale;

    public function __construct()
    {
        $this->pointsOfSale = new ArrayCollection();
    }

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

    public function getPointsOfSale(): Collection
    {
        return $this->pointsOfSale;
    }

    public function setPointsOfSale(Collection $pointsOfSale): PointType
    {
        $this->pointsOfSale = $pointsOfSale;
        return $this;
    }

    public function addPointOfSale(PointOfSale $pointOfSale): self
    {
        $this->pointsOfSale->add($pointOfSale);
        return $this;
    }
}