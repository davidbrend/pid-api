<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\ServiceRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
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
    #[ManyToOne(targetEntity: ServiceGroup::class, cascade: ['persist'], inversedBy: 'services')]
    #[JoinColumn(nullable: true)]
    protected ServiceGroup $serviceGroup;

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

    public function getServiceGroup(): ?ServiceGroup
    {
        return $this->serviceGroup;
    }

    public function setServiceGroup(ServiceGroup $serviceGroup): Service
    {
        $this->serviceGroup = $serviceGroup;
        return $this;
    }
}