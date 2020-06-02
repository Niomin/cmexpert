<?php

namespace App\SimilarOffer;

interface FinderInterface
{
    public function find(SimilarOffer $offer): OfferIterator;
}