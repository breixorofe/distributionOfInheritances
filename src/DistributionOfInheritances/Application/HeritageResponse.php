<?php

namespace App\DistributionOfInheritances\Application;

use App\Shared\Domain\Bus\Query\Response;

class HeritageResponse implements Response
{


    public function __construct(private int $stateTotalValue)
    {
    }

    public function __toString(): string
    {
        return json_encode(["stateTotalValue" => $this->stateTotalValue]);
    }


}