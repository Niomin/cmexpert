<?php

namespace App\Services;

use App\Entity\{Appraisal, Car};
use App\SimilarOffer\{FinderInterface, OfferIterator, SimilarOffer};
use App\Types\Price;
use App\Exception;
use Doctrine\ORM\EntityManagerInterface;

class AppraisalService
{
    const MAX_COMISSION_COUNT = 100;

    protected FinderInterface $finder;

    protected PriceCalculatorInterface $priceCalculator;

    protected EntityManagerInterface $em;

    public function __construct(
        FinderInterface $finder,
        PriceCalculatorInterface $priceCalculator,
        EntityManagerInterface $em
    ) {
        $this->finder = $finder;
        $this->priceCalculator = $priceCalculator;
        $this->em = $em;
    }

    public function create(Car $car, Price $repairPrice, int $type, ?Price $estimatedPrice)
    {
        $this->checkType($type);

        return $this->saveAppraisal($car, $repairPrice, $type, $estimatedPrice);
    }

    public function refresh(Appraisal $appraisal): void
    {
        $car = $appraisal->getCar();

        $offerIterator = $this->getOfferIterators($car);

        $appraisal
            ->setMedianPrice($this->priceCalculator->getMedianPrice($car, $offerIterator))
            ->setBuyoutPrice($this->priceCalculator->calculateBuyoutPrice($appraisal));

        if ($offerIterator->count() > static::MAX_COMISSION_COUNT) {
            $appraisal->setType(Appraisal::TYPE_CONTRACT);
        }
    }

    public function update(Appraisal $appraisal, array $params = []): void
    {
        if (isset($params['repairPrice'])) {
            $appraisal->setRepairPrice(new Price($params['repairPrice']));
            $this->refresh($appraisal);
        }

        if (isset($params['type'])) {
            $this->checkType($params['type']);
            $appraisal->setType($params['type']);
        }

        if (isset($params['estimatedPrice'])) {
            $appraisal->setEstimatedPrice(new Price($params['estimatedPrice']));
        }

        $this->em->flush();
    }

    public function get(string $id): Appraisal
    {
        $appraisal = $this->em->find(Appraisal::class, $id);
        if (!$appraisal) {
            Exception::throwAppraisalNotFound($id);
        }

        return $appraisal;
    }

    protected function saveAppraisal(Car $car, Price $repairPrice, int $type, ?Price $estimatedPrice)
    {
        $appraisal = new Appraisal($car, $repairPrice, $type, $estimatedPrice);

        $this->refresh($appraisal);

        $this->em->persist($appraisal);
        $this->em->flush();

        return $appraisal;
    }

    protected function checkType(int $type)
    {
        if (!in_array($type, [Appraisal::TYPE_CONTRACT, Appraisal::TYPE_COMMISSION])) {
            Exception::throwWrongAppraisalType($type);
        }
    }

    protected function getOfferIterators(Car $car): OfferIterator
    {
        $similarOffer = new SimilarOffer($car->getModel(), $car->getYear(), $car->getMileage(), $car->getColor());
        return $offerIterator = $this->finder->find($similarOffer);
    }

    public function serialize(?Appraisal $appraisal = null)
    {
        if (!$appraisal) {
            return [];
        }

        return [
            'id'             => $appraisal->getId(),
            'medianPrice'    => $appraisal->getMedianPrice() ? $appraisal->getMedianPrice()->getValue() : null,
            'repairPrice'    => $appraisal->getRepairPrice() ? $appraisal->getRepairPrice()->getValue() : null,
            'buyoutPrice'    => $appraisal->getBuyoutPrice() ? $appraisal->getBuyoutPrice()->getValue() : null,
            'estimatedPrice' => $appraisal->getEstimatedPrice() ? $appraisal->getEstimatedPrice()->getValue() : null,
            'type'           => $appraisal->getType(),
        ];
    }
}