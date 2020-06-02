<?php

namespace App\SimilarOffer;


class OfferIterator implements \Iterator
{
    /** @var Offer[] */
    protected array $offers = [];

    protected bool $valid = false;

    public function add(Offer $offer)
    {
        $this->offers[] = $offer;
        $this->valid = true;
    }

    public function current(): Offer
    {
        return current($this->offers);
    }

    public function next()
    {
        $value = next($this->offers);
        if ($value === false) {
            $this->valid = false;
        }
    }

    public function key()
    {
        return key($this->offers);
    }

    public function valid()
    {
        return $this->valid;
    }

    public function rewind()
    {
        $this->valid = count($this->offers) > 0;
        return reset($this->offers);
    }

    public function toArray(): array
    {
        return $this->offers;
    }

    public function count(): int
    {
        return count($this->offers);
    }

}