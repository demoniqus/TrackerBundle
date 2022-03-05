<?php

namespace Evrinoma\UidBundle\Tests\Functional\Controller;


use Evrinoma\UidBundle\EvrinomaUidBundle;
use Evrinoma\UidBundle\Fixtures\FixtureInterface;
use Evrinoma\TestUtilsBundle\Action\ActionTestInterface;
use Evrinoma\TestUtilsBundle\Functional\AbstractFunctionalTest;
use Psr\Container\ContainerInterface;

/**
 * @group functional
 */
final class ApiControllerTest extends AbstractFunctionalTest
{
//region SECTION: Fields
    protected string $actionServiceName = 'evrinoma.' . EvrinomaUidBundle::UID_LC . '.test.functional.action.' . EvrinomaUidBundle::UID_LC;
//endregion Fields

//region SECTION: Protected
    protected function getActionService(ContainerInterface $container): ActionTestInterface
    {
        return $container->get($this->actionServiceName);
    }
//endregion Protected

//region SECTION: Public
    public function testPut(): void
    {
        $this->markTestSkipped('Put operation is not allowed for entity');
    }
    public function testPutUnprocessable(): void
    {
        $this->markTestSkipped('Put operation is not allowed for entity');
    }
    public function testPutNotFound(): void
    {
        $this->markTestSkipped('Put operation is not allowed for entity');
    }
//endregion Public

//region SECTION: Getters/Setters
    public static function getFixtures(): array
    {
        return [FixtureInterface::UID_FIXTURES];
    }
//endregion Getters/Setters
}