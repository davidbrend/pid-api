<?php

declare(strict_types=1);

namespace App\Base\Database;

abstract class BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function getArr(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param array<string, mixed> $values
     * @return $this
     */
    public function setArr(array $values): self
    {
        foreach ($values as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }

    public function __clone()
    {
        unset($this->id);
    }
}
