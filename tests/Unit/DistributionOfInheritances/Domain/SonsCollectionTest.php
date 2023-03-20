<?php

use App\DistributionOfInheritances\Domain\FamilyMember;
use App\DistributionOfInheritances\Domain\SonsCollection;
use PHPUnit\Framework\TestCase;

class SonsCollectionTest extends TestCase
{
    private SonsCollection $sonsCollection;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sonsCollection = new SonsCollection(
            [
                new FamilyMember(new DateTime("30-10-1943"), "Borja", [], []),
                new FamilyMember(new DateTime("30-10-1942"), "Cristian", [], []),
                new FamilyMember(new DateTime("30-10-1946"), "Pepe", [], [])
            ]
        );
    }

    public function testOrderSonsAsc()
    {
        $sonsOrdered = $this->sonsCollection->orderedFromYoungestToOldest();
        $this->assertTrue($sonsOrdered[0]->getBirthday() > $sonsOrdered[1]->getBirthday());
    }

    public function testOrderSonsDesc()
    {
        $sonsOrdered = $this->sonsCollection->orderedFromOldestToYoungest();

        $this->assertTrue($sonsOrdered[0]->getBirthday() < $sonsOrdered[1]->getBirthday());
    }


}