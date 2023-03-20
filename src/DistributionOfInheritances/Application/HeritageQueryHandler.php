<?php

namespace App\DistributionOfInheritances\Application;

use App\DistributionOfInheritances\Domain\FamilyStructure;
use App\Shared\Domain\Bus\Query\QueryHandler;
use Exception;

class HeritageQueryHandler implements QueryHandler
{

    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(HeritageQuery $heritageQuery): HeritageResponse
    {
        $family = $heritageQuery->getFamily();
        $familyStructure = FamilyStructure::fromArray($family);

        return new HeritageResponse(
            $familyStructure->get($heritageQuery->getName())->calculateTheTotalValueOfMyEstate()
        );
    }


}