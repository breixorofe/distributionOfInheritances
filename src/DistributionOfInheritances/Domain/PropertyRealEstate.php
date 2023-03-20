<?php

namespace App\DistributionOfInheritances\Domain;

class PropertyRealEstate extends Property
{


    function totalMonetaryValue(): int
    {
        return 1000000;
    }

    function value(): int
    {
        return 1;
    }

    function type(): PropertyType
    {
        return PropertyType::realState;
    }
}