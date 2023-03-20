<?php

namespace App\DistributionOfInheritances\Domain;

use App\DistributionOfInheritances\Domain\Exceptions\SonOlderThanFatherException;
use DateTime;
use Exception;

class FamilyMember
{

    private SonsCollection $sons;

    /**
     * @param DateTime $birthday
     * @param string $name
     * @param Property[] $properties
     * @param FamilyMember[] $sons
     * @throws Exception
     */
    public function __construct(
        private readonly DateTime $birthday,
        private readonly string $name,
        private array $properties,
        array $sons
    ) {
        $this->sons = new SonsCollection($sons);
        $this->guardSonsAreYoungerThanMe();
        if ($this->isDeath() && !empty($this->sons) && !empty($this->properties)) {
            $this->toDistributeProperties();
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getSon(string $name)
    {
        return $this->sons->getSon($name);
    }

    /**
     * @return DateTime
     */
    public function getBirthday(): DateTime
    {
        return $this->birthday;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    private function toDistributeProperties(): void
    {
        $immovableProperty = [];
        foreach ($this->properties as $property) {
            switch ($property->type()) {
                case PropertyType::land:
                    $this->getEldestSon()->inherit($property);
                    break;
                case PropertyType::realState:
                    $immovableProperty[] = $property;
                    break;
                case PropertyType::money:
                    $this->handOutMoney($property);
            }
        }
        $this->distributeImmovableProperties($immovableProperty);
        $this->properties = [];
    }

    /**
     * @param PropertyRealEstate[] $immovableProperty
     * @return void
     */
    private function distributeImmovableProperties(array $immovableProperty): void
    {
        $sons = $this->sons->orderedFromYoungestToOldest();
        while (!empty($immovableProperty)) {
            for ($i = 0; $i < count($sons) && !empty($immovableProperty); $i++) {
                $sons[$i]->inherit(array_pop($immovableProperty));
            }
            for ($i = (count($sons) - 1); $i != 0 && !empty($immovableProperty); $i--) {
                $sons[$i]->inherit(array_pop($immovableProperty));
            }
        }
    }

    private function isDeath(): bool
    {
        $dateInterval = $this->birthday->diff(new DateTime());
        return ($dateInterval->y >= 100);
    }

    private function handOutMoney(Property $property): void
    {
        if ($property->totalMonetaryValue() % $this->sons->count() == 0) {
            foreach ($this->sons->getSons() as $son) {
                $son->inherit(new PropertyMoney(($property->totalMonetaryValue() / $this->sons->count())));
            }
        } else {
            $remainder = $property->totalMonetaryValue() % $this->sons->count();
            $resultOfDivision = intval($property->totalMonetaryValue() / $this->sons->count());
            $values = array_pad([], $this->sons->count(), $resultOfDivision);
            $index = 0;
            while ($remainder != 0) {
                $values[$index]++;
                $index++;
                $remainder--;
            }
            foreach ($this->sons->getSons() as $key => $son) {
                $son->inherit(new PropertyMoney($values[$key]));
            }
        }
    }

    function inherit(Property $property): void
    {
        switch ($property->type()) {
            case PropertyType::money:
                if (!$this->sons->empty()) {
                    $half = $property->totalMonetaryValue() / 2;
                    $this->handOutMoney(new PropertyMoney($half));
                    $myProperty = $this->removePropertyType(PropertyType::money);
                    $half += $property->totalMonetaryValue() % 2;
                    $newProperty = new PropertyMoney(
                        $half + ($myProperty !== null ? $myProperty->totalMonetaryValue() : 0)
                    );
                    $this->properties[] = $newProperty;
                } else {
                    $this->properties[] = $property;
                }
                break;
            case PropertyType::land:
                $currentProperty = $this->removePropertyType(PropertyType::land);
                $totalM2 = $property->value();
                if ($currentProperty !== null) {
                    $totalM2 += $currentProperty->value();
                }

                $newProperty = new PropertyLand(
                    $totalM2
                );
                //var_dump($newProperty);
                $this->properties[] = $newProperty;
                break;
            case PropertyType::realState:
                $this->properties[] = $property;
                break;
        }
    }

    private function getEldestSon(): ?FamilyMember
    {
        $eldestSon = null;
        if (!$this->sons->empty()) {
            $sons = $this->sons->orderedFromOldestToYoungest();
            $eldestSon = $sons[0];
            if ($sons[0]->getBirthday() == $sons[1]->getBirthday()) {
                $names = ["{$sons[0]->getName()}" => 0, "{$sons[1]->getName()}" => 1];
                ksort($names);
                $eldestSon = $sons[$names[array_key_first($names)]];
            }
        }
        return $eldestSon;
    }

    private function removePropertyType(PropertyType $propertyType): ?Property
    {
        foreach ($this->properties as $key => $property) {
            if ($property->type() === $propertyType) {
                unset($this->properties[$key]);
                $this->properties = array_values($this->properties);
                return $property;
            }
        }
        return null;
    }

    public function calculateTheTotalValueOfMyEstate(): int
    {
        $total = 0;
        foreach ($this->properties as $property) {
            $total += $property->totalMonetaryValue();
        }
        return $total;
    }

    /**
     * @throws SonOlderThanFatherException
     */
    private function guardSonsAreYoungerThanMe(): void
    {
        foreach ($this->sons->orderedFromOldestToYoungest() as $son) {
            if ($this->birthday >= $son->birthday) {
                throw new SonOlderThanFatherException($this->name, $son->name);
            }
        }
    }

}