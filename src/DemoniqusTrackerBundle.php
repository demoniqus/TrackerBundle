<?php

namespace Demoniqus\TrackerBundle;


use Demoniqus\TrackerBundle\DependencyInjection\Compiler\Constraint\Property\TrackerPass;
use Demoniqus\TrackerBundle\DependencyInjection\Compiler\DecoratorPass;
use Demoniqus\TrackerBundle\DependencyInjection\Compiler\MapEntityPass;
use Demoniqus\TrackerBundle\DependencyInjection\DemoniqusTrackerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DemoniqusTrackerBundle extends Bundle
{
//region SECTION: Fields
    public const TRACKER_LC = 'tracker';
    public const VENDOR_PREFIX_LC = 'demoniqus';
    public const TRACKER_CC = 'Tracker';
    public const TRACKER_BUNDLE_CC = 'TrackerBundle';
    public const VENDOR_PREFIX_CC = 'Demoniqus';
//endregion Fields

//region SECTION: Public
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container
            ->addCompilerPass(new MapEntityPass($this->getNamespace(), $this->getPath()))
            ->addCompilerPass( new DecoratorPass())
            ->addCompilerPass(new TrackerPass())
            ;
    }
//endregion Public

//region SECTION: Getters/Setters
    public function getContainerExtension()
    {
        return $this->extension ?? ($this->extension = new DemoniqusTrackerExtension());
    }
//endregion Getters/Setters
}