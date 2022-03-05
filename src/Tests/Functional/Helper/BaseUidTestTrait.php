<?php

namespace Evrinoma\UidBundle\Tests\Functional\Helper;

use PHPUnit\Framework\Assert;

trait BaseUidTestTrait
{
//region SECTION: Private
    protected function assertGet(int $id): array
    {
        $find = $this->get($id);
        $this->testResponseStatusOK();

        $this->checkResult($find);

        return $find;
    }

    protected function createUid(): array
    {
        $query = static::getDefault();

        return $this->post($query);
    }

    protected function createConstraintBlankUid(): array
    {
        $query = static::getDefault(['uid' => '']);

        return $this->post($query);
    }


    protected function checkResult($entity): void
    {
        Assert::assertArrayHasKey('data', $entity);
        Assert::assertArrayHasKey('id', $entity['data']);
        Assert::assertArrayHasKey('uid', $entity['data']);
    }
//endregion Private
}