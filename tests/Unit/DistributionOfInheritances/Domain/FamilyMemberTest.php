<?php

use App\DistributionOfInheritances\Domain\Exceptions\SonOlderThanFatherException;
use App\DistributionOfInheritances\Domain\FamilyMember;
use App\DistributionOfInheritances\Domain\PropertyLand;
use App\DistributionOfInheritances\Domain\PropertyMoney;
use App\DistributionOfInheritances\Domain\PropertyRealEstate;
use App\DistributionOfInheritances\Domain\PropertyType;
use PHPUnit\Framework\TestCase;

class FamilyMemberTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testInheritingLandBetween2SiblingsBornOnTheSameDay()
    {
        $familyMember = new FamilyMember(
            new DateTime("16-03-1923"),
            "Alan",
            [new PropertyLand(100)],
            [
                new FamilyMember(
                    new DateTime("30-10-1943"),
                    "Cristian",
                    [],
                    []
                ),
                new FamilyMember(
                    new DateTime("30-10-1943"),
                    "Borja",
                    [],
                    []
                )

            ]
        );

        $son = $familyMember->getSon("Borja");
        $this->assertNotNull($son);

        $this->assertNotNull($son->getProperties()[0]);

        $this->assertEquals(PropertyType::land, $son->getProperties()[0]->type());
    }

    /**
     * @throws Exception
     */
    public function testInheritingLandBetweenSiblings()
    {
        $familyMember = new FamilyMember(
            new DateTime("16-03-1923"),
            "Alan",
            [new PropertyLand(100)],
            [
                new FamilyMember(
                    new DateTime("30-10-1943"),
                    "Cristian",
                    [],
                    []
                ),
                new FamilyMember(
                    new DateTime("30-09-1946"),
                    "Pepe",
                    [],
                    []
                ),
                new FamilyMember(
                    new DateTime("30-10-1940"),
                    "Antonio",
                    [],
                    []
                ),
                new FamilyMember(
                    new DateTime("30-10-1943"),
                    "Borja",
                    [],
                    []
                )

            ]
        );

        $son = $familyMember->getSon("Antonio");
        $this->assertNotNull($son);
        $this->assertNotNull($son->getProperties()[0]);

        $this->assertEquals(PropertyType::land, $son->getProperties()[0]->type());
    }

    /**
     * @throws Exception
     */
    public function testInheritingRealStateBetweenSiblings()
    {
        $familyMember = new FamilyMember(
            new DateTime("16-03-1923"),
            "Alan",
            [new PropertyRealEstate()],
            [
                new FamilyMember(
                    new DateTime("30-10-1943"),
                    "Cristian",
                    [],
                    []
                ),
                new FamilyMember(
                    new DateTime("30-09-1946"),
                    "Pepe",
                    [],
                    []
                ),
                new FamilyMember(
                    new DateTime("30-10-1940"),
                    "Antonio",
                    [],
                    []
                ),
                new FamilyMember(
                    new DateTime("30-10-1943"),
                    "Borja",
                    [],
                    []
                )

            ]
        );

        $son = $familyMember->getSon("Pepe");
        $this->assertNotNull($son);

        $this->assertNotNull($son->getProperties()[0]);

        $this->assertEquals(PropertyType::realState, $son->getProperties()[0]->type());
    }

    /**
     * @throws Exception
     */
    public function testInheritingMoneyBetweenSiblings()
    {
        $familyMember = new FamilyMember(
            new DateTime("16-03-1923"),
            "Alan",
            [new PropertyMoney(100000)],
            [
                new FamilyMember(
                    new DateTime("30-10-1943"),
                    "Cristian",
                    [],
                    []
                ),
                new FamilyMember(
                    new DateTime("30-10-1942"),
                    "Borja",
                    [],
                    [
                        new FamilyMember(new DateTime("20-10-1963"), "David", [], [
                            new FamilyMember(
                                new DateTime("28-10-1983"),
                                "Isac",
                                [],
                                []
                            ),
                            new FamilyMember(
                                new DateTime("30-10-1981"),
                                "Javier",
                                [],
                                []
                            )
                        ]),
                        new FamilyMember(new DateTime("23-03-1967"), "Edu", [], []),
                        new FamilyMember(new DateTime("11-02-1964"), "Fernando", [], []),
                    ]
                )

            ]
        );
        $son = $familyMember->getSon("Borja");
        $this->assertEquals(25000, $son->calculateTheTotalValueOfMyEstate());
        $this->assertEquals(4167, $son->getSon("David")->calculateTheTotalValueOfMyEstate());
        $this->assertEquals(8333, $son->getSon("Edu")->calculateTheTotalValueOfMyEstate());
        $this->assertEquals(2084, $son->getSon("David")->getSon("Isac")->calculateTheTotalValueOfMyEstate());
    }

    /**
     * @throws Exception
     */
    public function testMemberDoesNotAllowHisChildrenToBeOlderThanHimself()
    {
        $this->expectException(SonOlderThanFatherException::class);

        new FamilyMember(
            new DateTime("16-03-1923"),
            "Alan",
            [],
            [
                new FamilyMember(
                    new DateTime("30-10-1900"),
                    "Cristian",
                    [],
                    []
                ),

            ]
        );
    }
}