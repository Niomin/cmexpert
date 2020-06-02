<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"updated_at"})})
 * @ORM\HasLifecycleCallbacks()
 */
class Car extends TimestampedEntity
{
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
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="Model")
     */
    protected Model $model;

    /**
     * @var Appraisal|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Appraisal", inversedBy="car")
     */
    protected ?Appraisal $appraisal = null;

    /**
     * @var Color
     *
     * @ORM\ManyToOne(targetEntity="Color")
     */
    protected Color $color;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected int $year;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected string $VIN;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    protected int $mileage;

    /**
     * @param Model $model
     * @param Color $color
     * @param int $year
     * @param string $VIN
     * @param int $mileage
     */
    public function __construct(Model $model, Color $color, int $year, string $VIN, int $mileage)
    {
        $this->model = $model;
        $this->color = $color;
        $this->year = $year;
        $this->VIN = $VIN;
        $this->mileage = $mileage;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->color;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return string
     */
    public function getVIN(): string
    {
        return $this->VIN;
    }

    /**
     * @return int
     */
    public function getMileage(): int
    {
        return $this->mileage;
    }

    public function setAppraisal(Appraisal $appraisal): self
    {
        $this->appraisal = $appraisal;
        return $this;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getAppraisal(): ?Appraisal
    {
        return $this->appraisal;
    }

    public function setColor(Color $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function setVIN(string $VIN): self
    {
        $this->VIN = $VIN;
        return $this;
    }

    public function setMileage(int $mileage): self
    {
        $this->mileage = $mileage;
        return $this;
    }
}