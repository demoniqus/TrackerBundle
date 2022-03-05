<?php

namespace Evrinoma\UidBundle\Tests\Functional;

use Evrinoma\TestUtilsBundle\Kernel\AbstractApiKernel;
use Evrinoma\UidBundle\EvrinomaUidBundle;

/**
 * Kernel
 */
class Kernel extends AbstractApiKernel
{
//region SECTION: Fields
    protected string $bundlePrefix = EvrinomaUidBundle::UID_BUNDLE_CC;
    protected string $rootDir = __DIR__;
//endregion Fields

//region SECTION: Public
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array_merge(parent::registerBundles(), [new \Evrinoma\DtoBundle\EvrinomaDtoBundle(), new \Evrinoma\UidBundle\EvrinomaUidBundle()]);
    }

    protected function getBundleConfig(): array
    {
        return  ['framework.yaml', 'jms_serializer.yaml'];
    }
}
