<?php

namespace Demoniqus\UidBundle\DependencyInjection\Compiler;

use Demoniqus\UidBundle\DependencyInjection\EvrinomaUidExtension;
use Demoniqus\UidBundle\EvrinomaUidBundle;
use Demoniqus\UidBundle\Model\Uid\UidInterface;
use Evrinoma\UtilsBundle\DependencyInjection\Compiler\AbstractMapEntity;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MapEntityPass extends AbstractMapEntity implements CompilerPassInterface
{
//region SECTION: Public
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $this->setContainer($container);

        $driver                    = $container->findDefinition('doctrine.orm.default_metadata_driver');
        $referenceAnnotationReader = new Reference('annotations.reader');

        $this->cleanMetadata($driver, [EvrinomaUidExtension::ENTITY]);

        $entity = $container->getParameter(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.' . EvrinomaUidBundle::UID_LC . '.entity');
        if ((strpos($entity, EvrinomaUidExtension::ENTITY) !== false)) {
            $this->loadMetadata(
                $driver,
                $referenceAnnotationReader,
                '%s/Model/' . EvrinomaUidBundle::UID_CC,
                '%s/Entity/' . EvrinomaUidBundle::UID_CC
            );
            $this->addResolveTargetEntity([$entity => [UidInterface::class => [],],], false);
        }
    }
//endregion Public
}