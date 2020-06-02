<?php

namespace App\Command;

use App\Entity\Appraisal;
use App\Entity\Brand;
use App\Entity\Car;
use App\Entity\Color;
use App\Entity\Model;
use App\Types\Price;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Command\AbstractConfigCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FillDatabase extends AbstractConfigCommand
{
    const CAR_ID = '306e7608-0271-4214-81c5-348b8cf9c548';

    protected EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('database:fill');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $objects = [];
        $objects[] = $color = new Color('red');
        $objects[] = new Color('green');
        $objects[] = new Color('blue');

        $objects[] = $brand1 = new Brand('Toyota');
        $objects[] = $brand2 = new Brand('Mercedes');

        $objects[] = $model1 = new Model('Corolla', $brand1);
        $objects[] = $model2 = new Model('Ne Corolla', $brand2);

        foreach ($objects as $object) {
            $this->em->persist($object);
        }
        $this->em->flush();

        $car = new Car($model1, $color, 1980, 'zxc', 500000);
        $this->em->persist($car);

        $reflection = new \ReflectionClass(Car::class);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($car, Uuid::fromString(self::CAR_ID));

        $this->em->persist($car);
        $this->em->flush();

        $car = $this->em->find(Car::class, self::CAR_ID);

        $appraisal = new Appraisal($car, new Price(100000), Appraisal::TYPE_CONTRACT, new Price(10000));

        $this->em->persist($appraisal);
        $this->em->flush();

        return 0;
    }

}