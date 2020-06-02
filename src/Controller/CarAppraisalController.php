<?php

namespace App\Controller;

use App\Entity\Car;
use App\Services\AppraisalService;
use App\Services\CarService;
use App\Types\Price;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarAppraisalController extends BaseController
{
    const ITEMS_PER_PAGE = 30;

    protected AppraisalService $appraisalService;

    protected CarService $carService;

    /**
     * @param AppraisalService $appraisalService
     * @param CarService $carService
     */
    public function __construct(AppraisalService $appraisalService, CarService $carService)
    {
        $this->appraisalService = $appraisalService;
        $this->carService = $carService;
    }

    /**
     * @Route("/appraising-cars/create/{carId}")
     */
    public function createAction(string $carId, Request $request)
    {
        $car = $this->carService->get($carId);

        $estimatedPriceScalar = $request->get('estimatedPrice');
        $estimatedPrice = null;

        if ($estimatedPriceScalar) {
            $estimatedPrice = new Price($estimatedPriceScalar);
        }

        $this->appraisalService->create(
            $car,
            new Price($request->get('repairPrice')),
            $request->get('type'),
            $estimatedPrice,
        );

        return $this->show($car);
    }

    /**
     * @Route("/appraising-cars/{id}")
     */
    public function getAction(string $id)
    {
        $car = $this->carService->getAppraised($id);
        return $this->show($car);
    }

    /**
     * @Route("/appraising-cars/{id}/refresh")
     */
    public function refreshAction(string $id)
    {
        $car = $this->carService->getAppraised($id);
        $this->appraisalService->refresh($car->getAppraisal());
        return $this->show($car);
    }

    /**
     * @Route("/appraising-cars/{id}/update")
     */
    public function updateAction(string $id, Request $request)
    {
        $car = $this->carService->getAppraised($id);
        $this->carService->update(
            $car,
            [
                'model'   => $request->get('model'),
                'color'   => $request->get('color'),
                'year'    => $request->get('year'),
                'VIN'     => $request->get('VIN'),
                'mileage' => $request->get('mileage'),
            ]);

        $appraisal = $car->getAppraisal();
        $this->appraisalService->update(
            $appraisal,
            [
                'type'           => $request->get('type'),
                'repairPrice'    => $request->get('repairPrice'),
                'estimatedPrice' => $request->get('estimatedPrice'),
            ]
        );

        return $this->show($car);
    }

    /**
     * @Route("/appraising-cars/list/{page}", requirements={"page"="\d+"})
     */
    public function listAction(int $page, Request $request)
    {
        $cars = $this->carService->getList($page, $request->get('items', static::ITEMS_PER_PAGE));
        $result = [
            'list' => []
        ];
        foreach ($cars as $car) {
            $result['list'][] = $this->merge($car);
        }
        return new JsonResponse($result);
    }

    protected function show(Car $car): JsonResponse
    {
        return new JsonResponse(['appraisingCar' => $this->merge($car)]);
    }

    protected function merge(Car $car): array
    {
        return array_merge(
            $this->appraisalService->serialize($car->getAppraisal()),
            $this->carService->serialize(($car))
        );
    }
}