<?php

namespace App\DistributionOfInheritances\Domain\Exceptions;

use Exception;

class SonOlderThanFatherException extends Exception
{

    public function __construct(string $parent, $son)
    {
        parent::__construct("Child $son is older than parent $parent , it is not possible.");
    }
}