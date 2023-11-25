<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\ServiceRepository;
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
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ServiceRepository::class)]
#[Table(name: 'services')]
class Service extends BaseEntity
{
    #[Column]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;
    #[Column]
    protected int $val;
    #[Column(name: "`description`", length: 255)]
    protected string $desc;
    #[ManyToMany(targetEntity: ServiceGroup::class, mappedBy: 'services', cascade: ['persist'])]
    #[JoinTable(name: 'service_groups_services')]
    protected Collection $serviceGroups;

    public function __construct()
    {
        $this->serviceGroups = new ArrayCollection();
    }

    public function getVal(): int
    {
        return $this->val;
    }

    public function setVal(int $val): Service
    {
        $this->val = $val;
        return $this;
    }

    public function getDesc(): string
    {
        return $this->desc;
    }

    public function setDesc(string $desc): Service
    {
        $this->desc = $desc;
        return $this;
    }

    public function getServiceGroups(): Collection
    {
        return $this->serviceGroups;
    }

    public function setServiceGroups(Collection $serviceGroups): Service
    {
        $this->serviceGroups = $serviceGroups;
        return $this;
    }


    public function addServiceGroup(ServiceGroup $serviceGroup): self
    {
        $this->serviceGroups->add($serviceGroup);
        return $this;
    }
}