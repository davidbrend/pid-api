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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
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
    protected string $id;
    #[ManyToOne(targetEntity: PointType::class, cascade: ['persist'], inversedBy: 'services')]
    #[JoinColumn(nullable: true)]
    protected ?PointType $type = null;
    #[Column(length: 255)]
    protected string $name;
    #[Column(length: 255)]
    protected string $address;
    #[Column(type: 'decimal', precision: 10, scale: 7)]
    protected float $lat;
    #[Column(type: 'decimal', precision: 10, scale: 7)]
    protected float $lon;
    #[OneToMany(mappedBy: 'pointOfSale', targetEntity: ServiceGroup::class, cascade: ['persist'])]
    protected Collection $services;
    #[OneToMany(mappedBy: 'pointOfSale', targetEntity: PayMethod::class, cascade: ['persist'])]
    protected Collection $payMethods;
    #[OneToMany(mappedBy: 'pointOfSale', targetEntity: OpeningHours::class, cascade: ['persist'])]
    protected Collection $openingHours;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->openingHours = new ArrayCollection();
        $this->payMethods = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): PointOfSale
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): ?PointType
    {
        return $this->type;
    }

    public function setType(?PointType $type): PointOfSale
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

    public function getOpeningHours(): Collection
    {
        return $this->openingHours;
    }

    /**
     * @param array<OpeningHours> $openingHours
     * @return $this
     */
    public function setOpeningHours(array $openingHours): PointOfSale
    {
        $this->openingHours = new ArrayCollection($openingHours);
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

    /**
     * @param array<ServiceGroup> $services
     * @return $this
     */
    public function setServices(array $services): PointOfSale
    {
        $this->services = new ArrayCollection($services);
        return $this;
    }

    public function getPayMethods(): Collection
    {
        return $this->payMethods;
    }

    /**
     * @param array<PayMethod> $payMethods
     * @return $this
     */
    public function setPayMethods(array $payMethods): PointOfSale
    {
        $this->payMethods = new ArrayCollection($payMethods);
        return $this;
    }
}