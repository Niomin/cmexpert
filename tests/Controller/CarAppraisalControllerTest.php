<?php

namespace App\Tests\Controller;

use App\Command\FillDatabase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class CarAppraisalControllerTest extends WebTestCase
{
    public function testGet()
    {
        $client = static::createClient();

        $id = FillDatabase::CAR_ID;
        $client->request('GET', "/appraising-cars/$id");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent(), true);
        $car = $data['appraisingCar'];

        $this->assertEquals($car['VIN'], 'zxc');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $id = FillDatabase::CAR_ID;

        $this->doUpdate($client, $id, 10000, 100000);
        $this->doUpdate($client, $id, 500000, 50000);

    }

    private function doUpdate(AbstractBrowser $client, string $id, int $mileage, int $estimatedPrice): void
    {
        $client->request('GET', "/appraising-cars/$id/update", [
            'mileage'        => $mileage,
            'estimatedPrice' => $estimatedPrice,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true)['appraisingCar'];
        $this->assertEquals($mileage, $data['mileage']);
        $this->assertEquals($estimatedPrice, $data['estimatedPrice']);
    }

}