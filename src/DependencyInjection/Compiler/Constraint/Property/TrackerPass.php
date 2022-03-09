<?php

namespace Demoniqus\TrackerBundle\DependencyInjection\Compiler\Constraint\Property;

use Demoniqus\TrackerBundle\DemoniqusTrackerBundle;
use Demoniqus\TrackerBundle\Validator\TrackerValidator;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractConstraint;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TrackerPass extends AbstractConstraint implements CompilerPassInterface
{
//region SECTION: Fields
        public const TRACKER_CONSTRAINT = DemoniqusTrackerBundle::VENDOR_PREFIX_LC . '.' . DemoniqusTrackerBundle::TRACKER_LC . '.constraint.property';

        protected static string $alias = self::TRACKER_CONSTRAINT;
        protected static string $class = TrackerValidator::class;
        protected static string $methodCall = 'addPropertyConstraint';
//endregion Fields
}