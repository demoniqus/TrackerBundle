<?php


namespace Demoniqus\TrackerBundle\Constraint\Property;


use Demoniqus\TrackerBundle\Model\ModelInterface;
use Evrinoma\UtilsBundle\Constraint\Property\ConstraintInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class Track implements ConstraintInterface
{
//region SECTION: Getters/Setters
    public function getConstraints(): array
    {
        return [
            new NotBlank(),
        ];
    }

    public function getPropertyName(): string
    {
        return ModelInterface::TRACK;
    }
//endregion Getters/Setters
}