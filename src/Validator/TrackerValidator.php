<?php

namespace Demoniqus\TrackerBundle\Validator;


use Demoniqus\TrackerBundle\Entity\Tracker\BaseTracker;
use Evrinoma\UtilsBundle\Validator\AbstractValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TrackerValidator extends AbstractValidator
{
//region SECTION: Fields
    protected static ?string $entityClass = BaseTracker::class;
//endregion Fields

//region SECTION: Constructor
    /**
     * @param ValidatorInterface $validator
     * @param string             $entityClass
     */
    public function __construct(ValidatorInterface $validator, string $entityClass)
    {
        parent::__construct($validator, $entityClass);
    }
//endregion Constructor
}