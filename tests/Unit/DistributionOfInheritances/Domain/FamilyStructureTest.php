<?php

use App\DistributionOfInheritances\Domain\Exceptions\FamilyMemberDuplicatedException;
use App\DistributionOfInheritances\Domain\Exceptions\FamilyMemberNotExistException;
use App\DistributionOfInheritances\Domain\FamilyStructure;
use PHPUnit\Framework\TestCase;

class FamilyStructureTest extends TestCase
{
    /**
     * @throws Exception
     * @dataProvider jsonFamilyStructureDataProvider
     */
    public function testCreateFamilyStructureSuccess(string $json)
    {
        $familyStructure = FamilyStructure::fromArray(json_decode($json, true));

        $this->assertCount(10, $familyStructure->getStructure());
    }

    /**
     * @throws Exception
     * @dataProvider jsonFamilyStructureSameNameDataProvider
     */
    public function testNameMayNotBeRepeatedInTheSameFamily(string $json)
    {
        $this->expectException(FamilyMemberDuplicatedException::class);
        FamilyStructure::fromArray(json_decode($json, true));
    }

    /**
     * @throws Exception
     * @dataProvider jsonFamilyStructureDataProvider
     */
    public function testFamilyMemberNotExistException(string $json)
    {
        $familyStructure = FamilyStructure::fromArray(json_decode($json, true));
        $this->expectException(FamilyMemberNotExistException::class);
        $familyStructure->get("Laura");
    }


    public function jsonFamilyStructureDataProvider(): array
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

    public function jsonFamilyStructureSameNameDataProvider(): array
    {
        return [
            [
                '{
                   "name": "Alan",
                   "birthday": "16-03-1923",
                   "properties": [
                      {
                         "type": "land",
                         "m2": 100
                      },
                      {
                         "type": "real state"
                      },
                      {
                         "type": "money",
                         "amount": 3000
                      }
                   ],
                   "sons": [
                      {
                         "name": "Alan",
                         "birthday": "30-10-1990",
                         "properties": [
                            {
                               "type": "land",
                               "m2": 100
                            },
                            {
                               "type": "real state"
                            },
                            {
                               "type": "money",
                               "amount": 3000
                            }
                         ],
                         "sons": []
                      },
                      {
                         "name": "Cristian",
                         "birthday": "30-10-1990",
                         "properties": [
                            {
                               "type": "land",
                               "m2": 100
                            },
                            {
                               "type": "real state"
                            },
                            {
                               "type": "money",
                               "amount": 3000
                            }
                         ],
                         "sons": []
                      }
                   ]
                }'
            ]
        ];
    }
}