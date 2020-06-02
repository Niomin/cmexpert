<?php

namespace App\Entity;

use App\Types\Price;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Appraisal extends TimestampedEntity
{
    const TYPE_CONTRACT = 1;
    const TYPE_COMMISSION = 2;

    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected UuidInterface $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Car", inversedBy="appraisal")
     */
    protected Car $car;

    /**
     * @ORM\Column(type="price", nullable=true)
     */
    protected ?Price $medianPrice = null;

    /**
     * @ORM\Column(type="price")
     */
    protected Price $repairPrice;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $type;

    /**
     * @ORM\Column(type="price", nullable=true)
     */
    protected ?Price $buyoutPrice = null;

    /**
     * @ORM\Column(type="price", nullable=true)
     */
    protected ?Price $estimatedPrice = null;

    public function __construct(Car $car, Price $repairPrice, int $type, ?Price $estimatedPrice)
    {
        $this->car = $car;
        $car->setAppraisal($this);
        $this->repairPrice = $repairPrice;
        $this->estimatedPrice = $estimatedPrice;
        $this->type = $type;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getMedianPrice(): ?Price
    {
        return $this->medianPrice;
    }

    public function getRepairPrice(): Price
    {
        return $this->repairPrice;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setMedianPrice(Price $medianPrice): self
    {
        $this->medianPrice = $medianPrice;
        return $this;
    }

    public function setType($type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getCar(): Car
    {
        return $this->car;
    }

    public function getEstimatedPrice(): ?Price
    {
        return $this->estimatedPrice;
    }

    public function getBuyoutPrice(): ?Price
    {
        return $this->buyoutPrice;
    }

    public function setBuyoutPrice(Price $buyoutPrice): self
    {
        $this->buyoutPrice = $buyoutPrice;
        return $this;
    }

    public function setRepairPrice(Price $price): self
    {
        $this->repairPrice = $price;
        return $this;
    }

    public function setEstimatedPrice(Price $estimatedPrice): self
    {
        $this->estimatedPrice = $estimatedPrice;
        return $this;
    }


}