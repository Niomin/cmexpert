<?php

namespace App\Tests\Controller;

use App\Command\FillDatabase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class CarControllerTest extends WebTestCase
{

    public function testGetAction()
    {
        $client = static::createClient();

        $car = $this->doGetCar($client);

        $this->assertEquals($car['id'], FillDatabase::CAR_ID);
        $this->assertEquals($car['VIN'], 'zxc');
    }

    protected function doGetCar(AbstractBrowser $client)
    {
        $id = FillDatabase::CAR_ID;
        $client->request('GET', "/car/1$id");
        $this->assertEquals(500, $client->getResponse()->getStatusCode());

        $client->request('GET', "/car/$id");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);
        return $data['car'];
    }

    public function testUpdateAction()
    {
        $client = static::createClient();

        $this->doTestUpdate($client, 500);

        $this->doTestUpdate($client, 500000);
    }

    public function testCreateAction()
    {
        $client = static::createClient();

        $car = [
            'VIN'     => 'zxc',
            'model'   => 1,
            'color'   => 1,
            'mileage' => 1000,
            'year'    => 1900,
        ];

        $client->request('GET', '/car/create', $car);
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    protected function doTestUpdate(AbstractBrowser $client, int $mileage)
    {
        $id = FillDatabase::CAR_ID;
        $client->request('GET', "/car/$id/update", ['mileage' => $mileage]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent(), true);
        $car = $data['car'];
        $this->assertEquals($car['mileage'], $mileage);
    }
}