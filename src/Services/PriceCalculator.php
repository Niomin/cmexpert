<?php

namespace App\Services;

use App\Entity\Appraisal;
use App\Entity\Car;
use App\SimilarOffer\Offer;
use App\SimilarOffer\OfferIterator;
use App\Types\Price;

class PriceCalculator implements PriceCalculatorInterface
{
    const BUYOUT_CONTRACT_MULTIPLIER = 1.1;

    public function getMedianPrice(Car $car, OfferIterator $offerIterator): Price
    {
        $medianPrice = $this->getSimilarOfferMedianPrice($offerIterator);
        $medianMileage = $this->getSimilarOfferMedianMileage($offerIterator);
        return $medianPrice->multiply($medianMileage / $car->getMileage());
    }

    public function calculateBuyoutPrice(Appraisal $appraisal): Price
    {
        $buyoutPrice = $appraisal->getMedianPrice()->substract($appraisal->getRepairPrice());

        if ($appraisal->getType() == Appraisal::TYPE_CONTRACT) {
            $buyoutPrice = $buyoutPrice->multiply(static::BUYOUT_CONTRACT_MULTIPLIER);
        }

        return $buyoutPrice;
    }

    protected function getSimilarOfferMedianPrice(OfferIterator $offerIterator): Price
    {
        return $this->getMedian($offerIterator, function(Offer $offer1, Offer $offer2) {
            return Price::compare($offer1->getPrice(), $offer2->getPrice());
        })->getPrice();
    }

    protected function getSimilarOfferMedianMileage(OfferIterator $offerIterator): int
    {
        return $this->getMedian($offerIterator, function(Offer $offer1, Offer $offer2) {
            return $offer1->getMileage() > $offer2->getMileage() ? 1 : -1;
        })->getMileage();
    }

    protected function getMedian(OfferIterator $offerIterator, callable $sort): Offer
    {
        $offers = $offerIterator->toArray();
        usort($offers, $sort);
        return $offers[ceil((count($offers) - 1)/2)];

    }
}