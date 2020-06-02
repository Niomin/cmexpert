<?php

namespace App\Tests\SimilarOffer;

use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\Model;
use App\SimilarOffer\Finder;
use App\SimilarOffer\SimilarOffer;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{
    public function testFind()
    {
        $finder = new Finder();
        $brand = new Brand('asd');
        $model = new Model('def', $brand);
        $color = new Color('qwe');

        $offer = new SimilarOffer($model, 1980, 500, $color);
        $iterator = $finder->find($offer);
        $this->assertEquals(150, $iterator->count());

        $offer = new SimilarOffer($model, 1981, 500, $color);
        $iterator = $finder->find($offer);
        $this->assertEquals(50, $iterator->count());
    }
}
