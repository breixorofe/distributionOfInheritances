<?php

namespace App\DistributionOfInheritances\Domain\Exceptions;

use Exception;

class FamilyMemberNotExistException extends Exception
{

    public function __construct(string $member)
    {
        parent::__construct("Member $member is not in the given family structure");
    }
}