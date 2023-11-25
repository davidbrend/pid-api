<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\ServiceGroupRepository;
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

// #[Entity(repositoryClass: ServiceGroupRepository::class)]
// #[Table(name: 'serviceGroups')]
class ServiceGroup extends BaseEntity
{
    #[Column]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;
    #[Column(name: "`description`", length: 255)]
    protected string $desc;
    #[OneToMany(mappedBy: 'serviceGroup', targetEntity: Service::class)]
    protected Collection $services;
    #[ManyToMany(targetEntity: PointOfSale::class, mappedBy: 'serviceGroups', cascade: ['persist'])]
    #[JoinTable(name: 'point_of_sale_service_groups')]
    protected Collection $pointsOfSale;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->pointsOfSale = new ArrayCollection();
    }

    public function getDesc(): string
    {
        return $this->desc;
    }

    public function setDesc(string $desc): ServiceGroup
    {
        $this->desc = $desc;
        return $this;
    }

    public function getServices(): Collection
    {
        return $this->services;
    }

    /**
     * @param array<Service> $services
     * @return $this
     */
    public function setServices(array $services): ServiceGroup
    {
        $this->services = new ArrayCollection($services);
        return $this;
    }

    public function getPointsOfSale(): Collection
    {
        return $this->pointsOfSale;
    }

    public function setPointsOfSale(Collection $pointsOfSale): ServiceGroup
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