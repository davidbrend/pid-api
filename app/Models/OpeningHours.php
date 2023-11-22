<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\OpeningHoursRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: OpeningHoursRepository::class)]
#[Table(name: 'openingHours')]
class OpeningHours extends BaseEntity
{
    #[Column]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;
    #[Column]
    protected int $from;
    #[Column]
    protected int $to;
    #[Column(type:'text')]
    protected string $hours;
    #[OneToOne(inversedBy: 'PointType', targetEntity: PointOfSale::class)]
    #[JoinColumn(nullable: false)]
    protected PointOfSale $pointOfSale;

    public function getFrom(): int
    {
        return $this->from;
    }

    public function setFrom(int $from): OpeningHours
    {
        $this->from = $from;
        return $this;
    }

    public function getTo(): int
    {
        return $this->to;
    }

    public function setTo(int $to): OpeningHours
    {
        $this->to = $to;
        return $this;
    }

    public function getHours(): string
    {
        return $this->hours;
    }

    public function setHours(string $hours): OpeningHours
    {
        $this->hours = $hours;
        return $this;
    }

    public function getPointOfSale(): PointOfSale
    {
        return $this->pointOfSale;
    }

    public function setPointOfSale(PointOfSale $pointOfSale): OpeningHours
    {
        $this->pointOfSale = $pointOfSale;
        return $this;
    }
}