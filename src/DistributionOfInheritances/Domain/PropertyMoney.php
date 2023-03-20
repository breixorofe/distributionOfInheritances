<?php

namespace App\DistributionOfInheritances\Domain;

class PropertyMoney extends Property
{

    public function __construct(private readonly int $amount)
    {
    }

    function totalMonetaryValue(): int
    {
        return $this->amount;
    }

    function value(): int
    {
        return $this->amount;
    }

    function type(): PropertyType
    {
        return PropertyType::money;
    }
}