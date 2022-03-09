<?php

namespace Demoniqus\TrackerBundle\DependencyInjection;


use Demoniqus\TrackerBundle\DemoniqusTrackerBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
//region SECTION: Getters/Setters
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder      = new TreeBuilder(DemoniqusTrackerBundle::TRACKER_LC);
        $rootNode         = $treeBuilder->getRootNode();
        $supportedDrivers = ['orm'];

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('db_driver')
            ->validate()
            ->ifNotInArray($supportedDrivers)
            ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
            ->end()
            ->cannotBeOverwritten()
            ->defaultValue('orm')
            ->end()
            ->scalarNode('factory')->cannotBeEmpty()->defaultValue(DemoniqusTrackerExtension::ENTITY_FACTORY)->end()
            ->scalarNode('entity')->cannotBeEmpty()->defaultValue(DemoniqusTrackerExtension::ENTITY_BASE)->end()
            ->scalarNode('constraints')->defaultTrue()->info('This option is used for enable/disable basic tracker constraints')->end()
            ->scalarNode('dto')->cannotBeEmpty()->defaultValue(DemoniqusTrackerExtension::DTO_BASE)->info('This option is used for dto class override')->end()
            ->arrayNode('decorates')->addDefaultsIfNotSet()->children()
            ->scalarNode('command')->defaultNull()->info('This option is used for command tracker decoration')->end()
            ->scalarNode('query')->defaultNull()->info('This option is used for query tracker decoration')->end()
            ->end()->end()->end();

        return $treeBuilder;
    }
//endregion Getters/Setters
}