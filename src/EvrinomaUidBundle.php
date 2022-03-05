<?php

namespace Demoniqus\UidBundle;


use Demoniqus\UidBundle\DependencyInjection\Compiler\Constraint\Property\UidPass;
use Demoniqus\UidBundle\DependencyInjection\Compiler\DecoratorPass;
use Demoniqus\UidBundle\DependencyInjection\Compiler\MapEntityPass;
use Demoniqus\UidBundle\DependencyInjection\EvrinomaUidExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EvrinomaUidBundle extends Bundle
{
//region SECTION: Fields
    public const UID_LC = 'uid';
    public const VENDOR_PREFIX_LC = 'evrinoma';
    public const UID_CC = 'Uid';
    public const UID_BUNDLE_CC = 'UidBundle';
    public const VENDOR_PREFIX_CC = 'Evrinoma';
//endregion Fields

//region SECTION: Public
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container
            ->addCompilerPass(new MapEntityPass($this->getNamespace(), $this->getPath()))
            ->addCompilerPass( new DecoratorPass())
            ->addCompilerPass(new UidPass())
            ;
    }
//endregion Public

//region SECTION: Getters/Setters
    public function getContainerExtension()
    {
        return $this->extension ?? ($this->extension = new EvrinomaUidExtension());
    }
//endregion Getters/Setters
}