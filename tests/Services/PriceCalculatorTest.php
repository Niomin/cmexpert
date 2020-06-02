<?php

namespace App\Tests\Services;

use App\Services\PriceCalculator;
use App\SimilarOffer\Offer;
use App\SimilarOffer\OfferIterator;
use App\Types\Price;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PriceCalculatorTest extends TestCase
{
    public function testGetMedianPrice()
    {
        $class = new ReflectionClass(PriceCalculator::class);
        $method = $class->getMethod('getSimilarOfferMedianPrice');
        $method->setAccessible(true);

        $priceCalculator = new PriceCalculator();
        $offerIterator = new OfferIterator();
        $prices = [1, 2, 2, 2, 3, 4, 5];
        shuffle($prices);
        foreach ($prices as $price) {
            $offerIterator->add(new Offer(100, new Price($price)));
        }

        $median = $method->invoke($priceCalculator, $offerIterator);
        $this->assertEquals(2, $median->getValue());

    }

    public function testGetMedianMileage()
    {
        $class = new ReflectionClass(PriceCalculator::class);
        $method = $class->getMethod('getSimilarOfferMedianMileage');
        $method->setAccessible(true);

        $priceCalculator = new PriceCalculator();
        $offerIterator = new OfferIterator();
        $mileages = [50, 100, 500, 1500, 2500, 5000, 10000, 15000, 500000, 700000];
        shuffle($mileages);
        foreach ($mileages as $mileage) {
            $offerIterator->add(new Offer($mileage, new Price(100)));
        }

        $median = $method->invoke($priceCalculator, $offerIterator);
        $this->assertEquals( 5000, $median);
    }
}
