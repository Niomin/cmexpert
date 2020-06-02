<?php

namespace App\SimilarOffer;

use App\Types\Price;

class Offer
{
    protected int $mileage;

    protected Price $price;

    /**
     * @param int $mileage
     * @param Price $price
     */
    public function __construct(int $mileage, Price $price)
    {
        $this->mileage = $mileage;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getMileage(): int
    {
        return $this->mileage;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }


}