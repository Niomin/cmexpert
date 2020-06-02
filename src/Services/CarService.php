<?php

namespace App\Services;

use App\Entity\Car;
use App\Entity\Color;
use App\Entity\Model;
use App\Exception;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarService
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(int $modelId, int $colorId, int $year, string $VIN, int $mileage)
    {
        $car = new Car($this->getModel($modelId), $this->getColor($colorId), $year, $VIN, $mileage);
        $this->em->persist($car);
        $this->em->flush();
        return $car;
    }

    protected function getModel(int $id): Model
    {
        /** @var Model $model */
        $model = $this->em->find(Model::class, $id);
        if (!$model) {
            Exception::throwModelNotFound($id);
        }
        return $model;
    }

    protected function getColor(int $id): Color
    {
        /** @var Color $color */
        $color = $this->em->find(Color::class, $id);
        if (!$color) {
            Exception::throwColorNotFound($id);
        }
        return $color;
    }

    public function get(string $id): Car
    {
        /** @var Car $car */
        $car = $this->em->find(Car::class, $id);
        if (!$car) {
            Exception::throwCarNotFound($id);
        }

        return $car;
    }

    public function getAppraised(string $id): Car
    {
        $car = $this->em->getRepository(Car::class)->findAppraised($id);
        if (!$car) {
            Exception::throwCarNotFound($id);
        }
        if (!$car->getAppraisal()) {
            Exception::throwCarNotAppraised($id);
        }

        return $car;
    }

    public function serialize(Car $car)
    {
        return [
            'id'      => $car->getId(),
            'model'   => $car->getModel()->getName(),
            'brand'   => $car->getModel()->getBrand()->getName(),
            'color'   => $car->getColor()->getValue(),
            'year'    => $car->getYear(),
            'VIN'     => $car->getVIN(),
            'mileage' => $car->getMileage(),
        ];

    }

    public function update(Car $car, array $params): Car
    {
        if (isset($params['model'])) {
            $car->setModel($this->getModel($params['model']));
        }

        if (isset($params['color'])) {
            $car->setColor($this->getColor($params['color']));
        }

        if (isset($params['VIN'])) {
            $car->setVIN($params['VIN']);
        }

        if (isset($params['year'])) {
            $car->setYear($params['year']);
        }

        if (isset($params['mileage'])) {
            $car->setMileage($params['mileage']);
        }

        $this->em->flush();
        return $car;
    }


    /**
     * @return Car[]
     */
    public function getList(int $page, int $itemsPerPage): array
    {
        /** @var CarRepository $repo */
        $repo = $this->em->getRepository(Car::class);
        return $repo->getAppraisedList($page, $itemsPerPage);
    }
}