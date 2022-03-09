<?php

namespace Demoniqus\TrackerBundle\Tests\Functional\Helper;

use Demoniqus\TrackerBundle\Model\ModelInterface;
use PHPUnit\Framework\Assert;

trait BaseTrackerTestTrait
{
//region SECTION: Private
    protected function assertGet(int $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected function createTracker(): array
    {
        $query = static::getDefault();

        return $this->post($query);
    }

    protected function createConstraintBlankTrack(): array
    {
        $query = static::getDefault([ModelInterface::TRACK => '']);

        return $this->post($query);
    }

    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey('data', $entity);
        Assert::assertArrayHasKey(ModelInterface::ID, $entity['data']);
        Assert::assertArrayHasKey(ModelInterface::TRACK, $entity['data']);
    }
//endregion Private
}