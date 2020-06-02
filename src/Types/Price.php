<?php

namespace App\Types;

/**
 * Не смог придумать, что лучше сделать с деньгами, но этот способ позволит не слишком болезненно расширять их функционал.
 */
class Price
{
    protected int $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->getvalue();
    }

    public static function compare(Price $p1, Price $p2): int
    {
        return $p1->getValue() > $p2->getvalue() ? 1 : -1;
    }

    public function multiply(float $multiplier)
    {
        return new self(round($this->getValue() * $multiplier));
    }

    public function substract(Price $price)
    {
        return new self($this->value - $price->getValue());
    }
}