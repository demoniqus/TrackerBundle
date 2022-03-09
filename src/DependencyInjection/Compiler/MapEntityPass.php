<?php

namespace Demoniqus\TrackerBundle\DependencyInjection\Compiler;

use Demoniqus\TrackerBundle\DependencyInjection\DemoniqusTrackerExtension;
use Demoniqus\TrackerBundle\DemoniqusTrackerBundle;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractMapEntity;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MapEntityPass extends AbstractMapEntity implements CompilerPassInterface
{
//region SECTION: Public
    /**
     * @inheritDoc
     * @throws \Evrinoma\UtilsBundle\Exception\MapEntityCannotBeCompiledException
     */
    public function process(ContainerBuilder $container)
    {
        $this->setContainer($container);

        $driver                    = $container->findDefinition('doctrine.orm.default_metadata_driver');
        $referenceAnnotationReader = new Reference('annotations.reader');

        $this->cleanMetadata($driver, [DemoniqusTrackerExtension::ENTITY]);

        $entity = $container->getParameter(DemoniqusTrackerBundle::VENDOR_PREFIX_LC . '.' . DemoniqusTrackerBundle::TRACKER_LC . '.entity');
        if ((strpos($entity, DemoniqusTrackerExtension::ENTITY) !== false)) {
            $this->loadMetadata(
                $driver,
                $referenceAnnotationReader,
                '%s/Model/' . DemoniqusTrackerBundle::TRACKER_CC,
                '%s/Entity/' . DemoniqusTrackerBundle::TRACKER_CC
            );
            $this->addResolveTargetEntity([$entity => [TrackerInterface::class => [],],], false);
        }
    }
//endregion Public
}