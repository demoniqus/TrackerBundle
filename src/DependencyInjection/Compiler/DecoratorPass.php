<?php

namespace Demoniqus\UidBundle\DependencyInjection\Compiler;

use Demoniqus\UidBundle\EvrinomaUidBundle;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DecoratorPass extends AbstractRecursivePass
{
//region SECTION: Public
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $decoratorQuery = $container->getParameter(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.' . EvrinomaUidBundle::UID_LC . '.decorates.query');
        if ($decoratorQuery) {
            $queryMediator = $container->getDefinition($decoratorQuery);
            $repository = $container->getDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.' . EvrinomaUidBundle::UID_LC . '.repository');
            $repository->setArgument(2, $queryMediator);
        }
        $decoratorCommand = $container->getParameter(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.' . EvrinomaUidBundle::UID_LC . '.decorates.command');
        if ($decoratorCommand) {
            $commandMediator = $container->getDefinition($decoratorCommand);
            $commandManager = $container->getDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.' . EvrinomaUidBundle::UID_LC . '.command.manager');
            $commandManager->setArgument(3, $commandMediator);
        }
    }
//endregion Public
}