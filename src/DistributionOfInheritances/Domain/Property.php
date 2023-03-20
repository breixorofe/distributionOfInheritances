<?php

namespace App\DistributionOfInheritances\Domain;

use Exception;

abstract class Property
{
    abstract function totalMonetaryValue(): int;

    abstract function value(): int;

    abstract function type(): PropertyType;

    /**
     * @throws Exception
     */
    static function fromArray($property): Property
    {
        $propertyType = PropertyType::from($property["type"]);
        switch ($propertyType) {
            case PropertyType::land:
                return new PropertyLand($property["m2"]);
            case PropertyType::money:
                return new PropertyMoney($property["amount"]);
            case PropertyType::realState:
                return new PropertyRealEstate();
        }
        throw new Exception("ERRRRRRRR");
    }
}