<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\PointOfSaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: PointOfSaleRepository::class)]
#[Table(name: 'pointsOfSale')]
class PointOfSale extends BaseEntity
{
    #[Column(type: 'string')]
    #[Id]
    #[GeneratedValue(strategy: 'NONE')]
    protected int $id;
    #[OneToOne(mappedBy: 'pointOfSale', targetEntity: PointType::class)]
    protected ?PointType $type = null;
    #[Column(type: 'text')]
    protected string $name;
    #[Column(type: 'text')]
    protected string $address;
    #[OneToOne(mappedBy: 'pointOfSale', targetEntity: OpeningHours::class)]
    protected OpeningHours $openingHours;
    #[Column(type: 'decimal', precision: 10, scale: 7)]
    protected float $lat;
    #[Column(type: 'decimal', precision: 10, scale: 7)]
    protected float $lon;
    #[OneToMany(mappedBy: 'pointOfSale', targetEntity: ServiceGroup::class)]
    protected Collection $services;
    #[OneToMany(mappedBy: 'pointOfSale', targetEntity: PayMethod::class)]
    protected Collection $payMethods;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->payMethods = new ArrayCollection();
    }

    public function getType(): PointType
    {
        return $this->type;
    }

    public function setType(PointType $type): PointOfSale
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PointOfSale
    {
        $this->name = $name;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): PointOfSale
    {
        $this->address = $address;
        return $this;
    }

    public function getOpeningHours(): OpeningHours
    {
        return $this->openingHours;
    }

    public function setOpeningHours(OpeningHours $openingHours): PointOfSale
    {
        $this->openingHours = $openingHours;
        return $this;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): PointOfSale
    {
        $this->lat = $lat;
        return $this;
    }

    public function getLon(): float
    {
        return $this->lon;
    }

    public function setLon(float $lon): PointOfSale
    {
        $this->lon = $lon;
        return $this;
    }

    public function getServices(): Collection
    {
        return $this->services;
    }

    public function setServices(Collection $services): PointOfSale
    {
        $this->services = $services;
        return $this;
    }

    public function getPayMethods(): Collection
    {
        return $this->payMethods;
    }

    public function setPayMethods(Collection $payMethods): PointOfSale
    {
        $this->payMethods = $payMethods;
        return $this;
    }
}