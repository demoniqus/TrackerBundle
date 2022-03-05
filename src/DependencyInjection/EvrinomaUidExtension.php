<?php

namespace Demoniqus\UidBundle\DependencyInjection;

use Demoniqus\UidBundle\DependencyInjection\Compiler\Constraint\Property\UidPass;
use Demoniqus\UidBundle\Dto\UidApiDto;
use Demoniqus\UidBundle\EvrinomaUidBundle;
use Evrinoma\UtilsBundle\DependencyInjection\HelperTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class EvrinomaUidExtension extends Extension
{
    use HelperTrait;
//region SECTION: Fields
    public const ENTITY         = EvrinomaUidBundle::VENDOR_PREFIX_CC . '\\' . EvrinomaUidBundle::UID_BUNDLE_CC . '\Entity';
    public const ENTITY_FACTORY = EvrinomaUidBundle::VENDOR_PREFIX_CC . '\\' . EvrinomaUidBundle::UID_BUNDLE_CC . '\Factory\UidFactory';
    public const ENTITY_BASE    = self::ENTITY.'\\' . EvrinomaUidBundle::UID_CC . '\BaseUid';
    public const DTO_BASE       = UidApiDto::class;

    /**
     * @var array
     */
    private static array $doctrineDrivers = array(
        'orm' => array(
            'registry' => 'doctrine',
            'tag'      => 'doctrine.event_subscriber',
        ),
    );
//endregion Fields

//region SECTION: Public
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ($container->getParameter('kernel.environment') !== 'prod') {
            $loader->load('fixtures.yml');
        }

        if ($container->getParameter('kernel.environment') === 'test') {
            $loader->load('tests.yml');
        }

        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        if ($config['factory'] !== self::ENTITY_FACTORY) {
            $this->wireFactory($container, $config['factory'], $config['entity']);
        } else {
            $definitionFactory = $container->getDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.factory');
            $definitionFactory->setArgument(0, $config['entity']);
        }

        $doctrineRegistry = null;

        if (isset(self::$doctrineDrivers[$config['db_driver']])) {
            $loader->load('doctrine.yml');
            $container->setAlias(
                EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.doctrine_registry',
                new Alias(self::$doctrineDrivers[$config['db_driver']]['registry'], false)
            );
            $doctrineRegistry = new Reference(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.doctrine_registry');
            $container->setParameter(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.backend_type_'. $config['db_driver'], true);
            $objectManager = $container->getDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.object_manager');
            $objectManager->setFactory([$doctrineRegistry, 'getManager']);
        }

        $this->remapParametersNamespaces(
            $container,
            $config,
            [
                '' => [
                    'db_driver' => EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.storage',
                    'entity'    => EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.entity',
                ],
            ]
        );

        if ($doctrineRegistry) {
            $this->wireRepository($container, $doctrineRegistry, $config['entity']);
        }

        $this->wireController($container, $config['dto']);

        $this->wireValidator($container, $config['entity']);

        $loader->load('validation.yml');

        if ($config['constraints']) {
            $loader->load('constraint/property/uid.yml');
        }

        $this->wireConstraintTag($container);

        if ($config['decorates']) {
            $this->remapParametersNamespaces(
                $container,
                $config['decorates'],
                [
                    '' => [
                        'command' => EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.decorates.command',
                        'query'   => EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.decorates.query',
                    ],
                ]
            );
        }


    }

//endregion Public

    private function wireConstraintTag(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $key => $definition) {
            switch (true) {
                case strpos($key, UidPass::UID_CONSTRAINT) !== false :
                    $definition->addTag(UidPass::UID_CONSTRAINT);
                    break;
                default:
            }
        }
    }

    private function wireRepository(ContainerBuilder $container, Reference $doctrineRegistry, string $class): void
    {
        $definitionRepository    = $container->getDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.repository');
        $definitionQueryMediator = $container->getDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.query.mediator');
        $definitionRepository->setArgument(0, $doctrineRegistry);
        $definitionRepository->setArgument(1, $class);
        $definitionRepository->setArgument(2, $definitionQueryMediator);
    }

    private function wireFactory(ContainerBuilder $container, string $class, string $paramClass): void
    {
        $container->removeDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.factory');
        $definitionFactory = new Definition($class);
        $definitionFactory->addArgument($paramClass);
        $alias = new Alias(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.factory');
        $container->addDefinitions([EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.factory' => $definitionFactory]);
        $container->addAliases([$class => $alias]);
    }

    private function wireController(ContainerBuilder $container, string $class): void
    {
        $definitionApiController = $container->getDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.api.controller');
        $definitionApiController->setArgument(5, $class);
    }

    private function wireValidator(ContainerBuilder $container, string $class): void
    {
        $definitionApiController = $container->getDefinition(EvrinomaUidBundle::VENDOR_PREFIX_LC . '.'.$this->getAlias().'.validator');
//        $definitionApiController->setArgument(0, $class);
        $definitionApiController->setArgument(0, new Reference('validator'));
        $definitionApiController->setArgument(1, $class);
    }

//region SECTION: Getters/Setters
    public function getAlias()
    {
        return EvrinomaUidBundle::UID_LC;
    }
//endregion Getters/Setters
}