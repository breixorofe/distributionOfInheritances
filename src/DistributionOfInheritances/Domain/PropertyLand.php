<?php

namespace App\DistributionOfInheritances\Domain;

class PropertyLand extends Property
{

    public function __construct(private readonly int $m2)
    {
    }

    function totalMonetaryValue(): int
    {
        return $this->m2 * 300;
    }

    function value(): int
    {
        return $this->m2;
    }

    function type(): PropertyType
    {
        return PropertyType::land;
    }
}