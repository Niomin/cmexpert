<?php

namespace App\SimilarOffer;

use App\Entity\Color;
use App\Entity\Model;
use App\Types\Price;

class SimilarOffer
{
    protected Model $model;

    protected int $year;

    protected int $mileage;

    protected Color $color;

    /**
     * @param Model $model
     * @param int $year
     * @param int $mileage
     * @param Color $color
     */
    public function __construct(Model $model, int $year, int $mileage, Color $color)
    {
        $this->model = $model;
        $this->year = $year;
        $this->mileage = $mileage;
        $this->color = $color;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getMileage(): int
    {
        return $this->mileage;
    }

    /**
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->color;
    }

}