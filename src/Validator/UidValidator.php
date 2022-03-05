<?php

namespace Evrinoma\UidBundle\Validator;


use Evrinoma\UidBundle\Entity\Uid\BaseUid;
use Evrinoma\UtilsBundle\Validator\AbstractValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UidValidator extends AbstractValidator
{
//region SECTION: Fields
    protected static ?string $entityClass = BaseUid::class;
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