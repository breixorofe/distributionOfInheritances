<?php

namespace App\DistributionOfInheritances\Domain\Exceptions;

use Exception;

class FamilyMemberDuplicatedException extends Exception
{

    public function __construct(string $member)
    {
        parent::__construct("There is more than one member with this name: $member");
    }
}