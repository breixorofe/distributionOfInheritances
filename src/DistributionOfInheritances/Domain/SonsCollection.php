<?php

namespace App\DistributionOfInheritances\Domain;

use Closure;
use DateTime;

class SonsCollection
{

    /**
     * @param FamilyMember[] $sons
     */
    public function __construct(private readonly array $sons)
    {
    }

    /**
     * @return array
     */
    public function getSons(): array
    {
        return $this->sons;
    }

    public function count(): int
    {
        return count($this->sons);
    }

    public function empty(): bool
    {
        return empty($this->sons);
    }

    /**
     * @return array
     */
    public function orderedFromYoungestToOldest(): array
    {
        $sons = $this->sons;
        usort($sons, $this->sonsSorter());
        return $sons;
    }

    public function orderedFromOldestToYoungest(): array
    {
        $sons = $this->sons;
        usort($sons, $this->sonsSorter("DESC"));
        return $sons;
    }


    private function sonsSorter(string $order = "ASC"): Closure
    {
        return function (FamilyMember $a, FamilyMember $b) use ($order) {
            $t1 = strtotime($a->getBirthday()->format("Y-m-d"));
            $t2 = strtotime($b->getBirthday()->format("Y-m-d"));

            return ($order == "DESC") ? $t1 - $t2 : $t2 - $t1;
        };
    }

    public function getSon(string $sonName): ?FamilyMember
    {
        foreach ($this->sons as $son) {
            if ($son->getName() === $sonName) {
                return $son;
            }
        }
        return null;
    }
}