<?php

namespace App\DistributionOfInheritances\Application;

use App\Shared\Domain\Bus\Query\Query;

readonly class HeritageQuery implements Query
{

    public function __construct(private string $name, private array $family)
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getFamily(): array
    {
        return $this->family;
    }


}