<?php

namespace App\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DecimalType;

class PriceType extends DecimalType
{
    const PRICE_PRECISION = 7;

    public function getName()
    {
        return 'price';
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if (empty($fieldDeclaration['precision'])) {
            $fieldDeclaration['precision'] = static::PRICE_PRECISION;
        }
        return parent::getSQLDeclaration($fieldDeclaration, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $output = parent::convertToPHPValue($value, $platform);
        return $output ? new Price($output) : null;
    }

    public function convertToDatabaseValue($price, AbstractPlatform $platform)
    {
        return $price ? $price->getValue() : null;
    }

}