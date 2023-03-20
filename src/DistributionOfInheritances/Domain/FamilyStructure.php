<?php

namespace App\DistributionOfInheritances\Domain;

use App\DistributionOfInheritances\Domain\Exceptions\FamilyMemberDuplicatedException;
use App\DistributionOfInheritances\Domain\Exceptions\FamilyMemberNotExistException;
use DateTime;
use Exception;

readonly class FamilyStructure
{

    /**
     * @param array<string, FamilyMember> $structure
     */
    private function __construct(private array $structure)
    {
    }

    /**
     * @param array<string,mixed> $family
     * @return FamilyStructure
     * @throws Exception
     */
    public static function fromArray(array $family): FamilyStructure
    {
        $structure = array();
        $familyMember = new FamilyMember(
            new DateTime($family["birthday"]),
            $family["name"],
            self::buildProperties($family["properties"]),
            self::buildSons($structure, $family["sons"])
        );
        self::guardMemberNotDuplicated($familyMember->getName(), $structure);
        $structure[$familyMember->getName()] = $familyMember;

        return new self($structure);
    }

    /**
     * @return array<string, FamilyMember>
     */
    public function getStructure(): array
    {
        return $this->structure;
    }

    /**
     * @throws FamilyMemberNotExistException
     */
    public function get(string $key): FamilyMember
    {
        $this->guardMemberExist($key);
        return $this->structure[$key];
    }

    /**
     * @throws FamilyMemberNotExistException
     */
    private function guardMemberExist(string $member): void
    {
        if (!array_key_exists($member, $this->structure)) {
            throw new FamilyMemberNotExistException($member);
        }
    }

    /**
     * @throws Exception
     */
    private static function buildSons(&$structure, $sons): array
    {
        $result = array();
        foreach ($sons as $son) {
            if (empty($son["sons"])) {
                $member = new FamilyMember(
                    new DateTime($son["birthday"]),
                    $son["name"],
                    self::buildProperties($son["properties"]),
                    []
                );
            } else {
                $member = new FamilyMember(
                    new DateTime($son["birthday"]),
                    $son["name"],
                    self::buildProperties($son["properties"]),
                    self::buildSons($structure, $son["sons"])
                );
            }
            $result[] = $member;
            self::guardMemberNotDuplicated($member->getName(), $structure);
            $structure[$member->getName()] = $member;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private static function buildProperties(array $properties): array
    {
        $result = array();
        foreach ($properties as $property) {
            $result[] = Property::fromArray($property);
        }
        return $result;
    }


    /**
     * @throws FamilyMemberDuplicatedException
     */
    private static function guardMemberNotDuplicated(string $member, array $structure): void
    {
        if (array_key_exists($member, $structure)) {
            throw new FamilyMemberDuplicatedException($member);
        }
    }
}