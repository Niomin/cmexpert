<?php

namespace App\SimilarOffer;

use App\Types\Price;

class Finder implements FinderInterface
{

    public function find(SimilarOffer $offer): OfferIterator
    {
        $count = $this->getCount($offer);

        $iterator = new OfferIterator();

        for ($i = 0; $i < $count; $i++) {
            $iterator->add(new Offer(
                random_int(0, 1000000),
                new Price(random_int(0, 1000000))
            ));
        }

        return $iterator;
    }

    /**
     * @param SimilarOffer $offer
     * @return int
     * @throws \Exception
     */
    protected function getCount(SimilarOffer $offer): int
    {
        switch ($offer->getYear()) {
            case 1980:
                $count = 150;
                break;
            case 1981:
                $count = 50;
                break;
            default:
                $count = random_int(1, 200);
        }
        return $count;
    }
}