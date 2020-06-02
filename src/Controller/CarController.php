<?php

namespace App\Controller;

use App\Entity\Car;
use App\Services\CarService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CarController extends BaseController
{
    protected CarService $service;

    /**
     * @param CarService $service
     */
    public function __construct(CarService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/car/create")
     */
    public function createAction(Request $request)
    {
        return $this->show($this->service->create(
            $request->get('model'),
            $request->get('color'),
            $request->get('year'),
            $request->get('VIN'),
            $request->get('mileage')
        ));
    }

    /**
     * @Route("/car/{id}")
     */
    public function getAction(string $id)
    {
        return $this->show($this->service->get($id));
    }

    /**
     * @Route("/car/{id}/update")
     */
    public function updateAction(string $id, Request $request)
    {
        return $this->show(
            $this->service->update(
                $this->service->get($id),
                [
                    'model'   => $request->get('model'),
                    'color'   => $request->get('color'),
                    'year'    => $request->get('year'),
                    'VIN'     => $request->get('VIN'),
                    'mileage' => $request->get('mileage'),
                ]
            ));
    }

    protected function show(Car $car)
    {
        return new JsonResponse([
            'car' => $this->service->serialize($car),
        ]);
    }
}

