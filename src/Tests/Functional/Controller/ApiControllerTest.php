<?php

namespace Demoniqus\TrackerBundle\Tests\Functional\Controller;


use Demoniqus\TrackerBundle\DemoniqusTrackerBundle;
use Demoniqus\TrackerBundle\Fixtures\FixtureInterface;
use Evrinoma\TestUtilsBundle\Action\ActionTestInterface;
use Evrinoma\TestUtilsBundle\Functional\AbstractFunctionalTest;
use Psr\Container\ContainerInterface;

/**
 * @group functional
 */
final class ApiControllerTest extends AbstractFunctionalTest
{
//region SECTION: Fields
    protected string $actionServiceName = DemoniqusTrackerBundle::VENDOR_PREFIX_LC . '.' . DemoniqusTrackerBundle::TRACKER_LC . '.test.functional.action.' . DemoniqusTrackerBundle::TRACKER_LC;
//endregion Fields

//region SECTION: Protected
    /**
     * @param ContainerInterface $container
     * @return ActionTestInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
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
        return [FixtureInterface::TRACKER_FIXTURES];
    }
//endregion Getters/Setters
}