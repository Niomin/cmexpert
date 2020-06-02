<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"brand_id", "name"})})
 */
class Model
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected string $name;

    /**
     * @var Brand
     *
     * @ORM\ManyToOne(targetEntity="Brand")
     */
    protected Brand $brand;

    /**
     * @param string $name
     * @param Brand $brand
     */
    public function __construct(string $name, Brand $brand)
    {
        $this->name = $name;
        $this->brand = $brand;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }
}