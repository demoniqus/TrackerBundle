<?php

namespace Demoniqus\UidBundle\DependencyInjection\Compiler\Constraint\Property;

use Demoniqus\UidBundle\EvrinomaUidBundle;
use Demoniqus\UidBundle\Validator\UidValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class UidPass extends AbstractConstraint implements CompilerPassInterface
{
//region SECTION: Fields
        public const UID_CONSTRAINT = EvrinomaUidBundle::VENDOR_PREFIX_LC . '.' . EvrinomaUidBundle::UID_LC . '.constraint.property';

        protected static string $alias = self::UID_CONSTRAINT;
        protected static string $class = UidValidator::class;
        protected static string $methodCall = 'addPropertyConstraint';
//endregion Fields
}