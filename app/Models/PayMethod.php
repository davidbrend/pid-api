<?php

namespace App\Models;

use App\Base\Database\BaseEntity;
use App\Repositories\PayMethodRepository;
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
    #[Column(name: "`description`", length: 255)]
    protected string $desc;
    #[ManyToMany(targetEntity: PointOfSale::class, mappedBy: 'payMethods', cascade: ['persist'])]
    #[JoinTable(name: 'point_of_sale_pay_methods')]
    protected Collection $pointsOfSale;

    public function __construct()
    {
        $this->pointsOfSale = new ArrayCollection();
    }

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

    public function getPointsOfSale(): Collection
    {
        return $this->pointsOfSale;
    }

    public function setPointsOfSale(Collection $pointsOfSale): PayMethod
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