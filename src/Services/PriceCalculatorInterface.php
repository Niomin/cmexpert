<?php

namespace App\Services;

use App\Entity\Car;
use App\SimilarOffer\OfferIterator;
use App\Types\Price;

interface PriceCalculatorInterface
{
    public function getMedianPrice(Car $car, OfferIterator $offerIterator): Price;
}