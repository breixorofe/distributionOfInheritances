<?php

declare(strict_types=1);

namespace App\Tests\Application\DistributionOfInheritances\Application;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class HeritageByNameGetControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }


    /**
     * @dataProvider  familyStructureProvider
     */
    public function testSimpleCaseInheritMoney(string $content)
    {
        $response = $this->apiRequest("Borja", $content);
        $this->assertEquals(25000, $response->stateTotalValue);

        $response = $this->apiRequest("David", $content);
        $this->assertEquals(4167, $response->stateTotalValue);

        $response = $this->apiRequest("Hector", $content);
        $this->assertEquals(12500, $response->stateTotalValue);


        $response = $this->apiRequest("Edu", $content);
        $this->assertEquals(8333, $response->stateTotalValue);


        $response = $this->apiRequest("Isac", $content);
        $this->assertEquals(2084, $response->stateTotalValue);

        $response = $this->apiRequest("Javier", $content);
        $this->assertEquals(2083, $response->stateTotalValue);
    }

    public static function familyStructureProvider(): array
    {
        return [
            [
                '{
                       "name": "Alan",
                       "birthday": "16-03-1923",
                       "properties": [
                          {
                             "type": "money",
                             "amount": 100000
                          }
                       ],
                       "sons": [
                          {
                             "name": "Borja",
                             "birthday": "30-10-1943",
                             "properties": [],
                             "sons": [
                                {
                                   "name": "David",
                                   "birthday": "30-10-1963",
                                   "properties": [],
                                   "sons": [
                                      {
                                         "name": "Isac",
                                         "birthday": "28-10-1983",
                                         "properties": [],
                                         "sons": []
                                      },
                                      {
                                         "name": "Javier",
                                         "birthday": "30-10-1981",
                                         "properties": [],
                                         "sons": []
                                      }
                                   ]
                                },
                                {
                                   "name": "Edu",
                                   "birthday": "30-10-1964",
                                   "properties": [],
                                   "sons": []
                                },
                                {
                                   "name": "Fernando",
                                   "birthday": "30-10-1965",
                                   "properties": [],
                                   "sons": []
                                }
                             ]
                          },
                          {
                             "name": "Cristian",
                             "birthday": "30-10-1942",
                             "properties": [],
                             "sons": [
                                {
                                   "name": "Gabi",
                                   "birthday": "30-10-1965",
                                   "properties": [],
                                   "sons": []
                                },
                                {
                                   "name": "Hector",
                                   "birthday": "30-10-1963",
                                   "properties": [],
                                   "sons": []
                                }
                             ]
                          }
                       ]
                    }'
            ]
        ];
    }

    /**
     * @dataProvider familyStructureWithAllPropertyTypesProvider
     */
    public function testInheritAllPropertyTypes(string $content)
    {
        $response = $this->apiRequest("Borja", $content);
        $this->assertEquals(1025000, $response->stateTotalValue);

        $response = $this->apiRequest("Cristian", $content);
        $this->assertEquals(55000, $response->stateTotalValue);


        $response = $this->apiRequest("David", $content);
        $this->assertEquals(4167, $response->stateTotalValue);


        $response = $this->apiRequest("Edu", $content);
        $this->assertEquals(8333, $response->stateTotalValue);

        $response = $this->apiRequest("Isac", $content);
        $this->assertEquals(2084, $response->stateTotalValue);
    }

    public static function familyStructureWithAllPropertyTypesProvider(): array
    {
        return [
            [
                '{
                       "name": "Alan",
                       "birthday": "16-03-1923",
                       "properties": [
                          {
                             "type": "money",
                             "amount": 100000
                          },
                          {
                             "type": "land",
                             "m2": 100
                          },
                          {
                             "type": "real state"
                          }
                       ],
                       "sons": [
                          {
                             "name": "Borja",
                             "birthday": "30-10-1943",
                             "properties": [],
                             "sons": [
                                {
                                   "name": "David",
                                   "birthday": "30-10-1963",
                                   "properties": [],
                                   "sons": [
                                      {
                                         "name": "Isac",
                                         "birthday": "28-10-1983",
                                         "properties": [],
                                         "sons": []
                                      },
                                      {
                                         "name": "Javier",
                                         "birthday": "30-10-1981",
                                         "properties": [],
                                         "sons": []
                                      }
                                   ]
                                },
                                {
                                   "name": "Edu",
                                   "birthday": "30-10-1964",
                                   "properties": [],
                                   "sons": []
                                },
                                {
                                   "name": "Fernando",
                                   "birthday": "30-10-1965",
                                   "properties": [],
                                   "sons": []
                                }
                             ]
                          },
                          {
                             "name": "Cristian",
                             "birthday": "30-10-1942",
                             "properties": [],
                             "sons": [
                                {
                                   "name": "Gabi",
                                   "birthday": "30-10-1965",
                                   "properties": [],
                                   "sons": []
                                },
                                {
                                   "name": "Hector",
                                   "birthday": "30-10-1963",
                                   "properties": [],
                                   "sons": []
                                }
                             ]
                          }
                       ]
                    }'
            ]
        ];
    }

    private function apiRequest($name, $content)
    {
        $this->client->request(
            'GET',
            "/api/heritage/$name",
            [],
            [],
            [],
            $content
        );
        $this->assertResponseIsSuccessful();
        return json_decode($this->client->getResponse()->getContent());
    }
}