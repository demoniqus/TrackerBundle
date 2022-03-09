<?php

namespace Demoniqus\TrackerBundle\DependencyInjection\Compiler;

use Demoniqus\TrackerBundle\DemoniqusTrackerBundle;
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
        $decoratorQuery = $container->getParameter(DemoniqusTrackerBundle::VENDOR_PREFIX_LC . '.' . DemoniqusTrackerBundle::TRACKER_LC . '.decorates.query');
        if ($decoratorQuery) {
            $queryMediator = $container->getDefinition($decoratorQuery);
            $repository = $container->getDefinition(DemoniqusTrackerBundle::VENDOR_PREFIX_LC . '.' . DemoniqusTrackerBundle::TRACKER_LC . '.repository');
            $repository->setArgument(2, $queryMediator);
        }
        $decoratorCommand = $container->getParameter(DemoniqusTrackerBundle::VENDOR_PREFIX_LC . '.' . DemoniqusTrackerBundle::TRACKER_LC . '.decorates.command');
        if ($decoratorCommand) {
            $commandMediator = $container->getDefinition($decoratorCommand);
            $commandManager = $container->getDefinition(DemoniqusTrackerBundle::VENDOR_PREFIX_LC . '.' . DemoniqusTrackerBundle::TRACKER_LC . '.command.manager');
            $commandManager->setArgument(3, $commandMediator);
        }
    }
//endregion Public
}