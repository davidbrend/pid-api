<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\OpeningHoursRepository;
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

#[Entity(repositoryClass: OpeningHoursRepository::class)]
#[Table(name: 'openingHours')]
class OpeningHours extends BaseEntity
{
    #[Column]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    protected int $id;
    #[Column(name: "`fromDay`")]
    protected int $from;
    #[Column(name: "`toDay`")]
    protected int $to;
    #[Column(name: "`dateRangeString`", length: 255)]
    protected string $hours;
    #[ManyToMany(targetEntity: PointOfSale::class, mappedBy: 'openingHours', cascade: ['persist'])]
    #[JoinTable(name: 'point_of_sale_opening_hours')]
    protected Collection $pointsOfSale;

    public function __construct()
    {
        $this->pointsOfSale = new ArrayCollection();
    }

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

    public function getPointsOfSale(): Collection
    {
        return $this->pointsOfSale;
    }

    /**
     * @param array<PointOfSale> $pointsOfSale
     * @return $this
     */
    public function setPointsOfSale(array $pointsOfSale): OpeningHours
    {
        $this->pointsOfSale = new ArrayCollection($pointsOfSale);
        return $this;
    }

    public function addPointOfSale(PointOfSale $pointOfSale): self
    {
        $this->pointsOfSale->add($pointOfSale);
        return $this;
    }
}