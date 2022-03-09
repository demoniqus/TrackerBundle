<?php

namespace Demoniqus\TrackerBundle\Tests\Functional\Action;


use Demoniqus\TrackerBundle\Dto\TrackerApiDto;
use Demoniqus\TrackerBundle\Model\ModelInterface;
use Demoniqus\TrackerBundle\Tests\Functional\Helper\BaseTrackerTestTrait;
use Demoniqus\TrackerBundle\DemoniqusTrackerBundle;
use Demoniqus\TrackerBundle\Tests\Functional\ValueObject\Track;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use PHPUnit\Framework\Assert;


class BaseTracker extends AbstractServiceTest implements BaseTrackerTestInterface
{
    use BaseTrackerTestTrait;

//region SECTION: Fields
    protected const API_PREFIX = DemoniqusTrackerBundle::VENDOR_PREFIX_LC . '/api/' . DemoniqusTrackerBundle::TRACKER_LC;
    public const API_GET      = self::API_PREFIX;
    public const API_CRITERIA = self::API_PREFIX . '/criteria';
    public const API_DELETE   = self::API_PREFIX . '/delete';
    public const API_PUT      = self::API_PREFIX . '/save';
    public const API_POST     = self::API_PREFIX . '/create';
//endregion Fields

//region SECTION: Public
    /**
     * @return array
     */
    public static function defaultData(): array
    {
        return [
            ModelInterface::TRACK  => crypt(Track::value(), 'salt'),
            DtoInterface::DTO_CLASS => TrackerApiDto::class,
        ];
    }

    public function actionPost(): void
    {
        $this->createTracker();
        $this->testResponseStatusCreated();
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria([DtoInterface::DTO_CLASS => static::getDtoClass(), ModelInterface::ACTIVE => ActiveModel::ACTIVE, ModelInterface::ID => 1]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find['data']);

        $find = $this->criteria([DtoInterface::DTO_CLASS => static::getDtoClass(), ModelInterface::ACTIVE => ActiveModel::ACTIVE]);
        $this->testResponseStatusOK();
        Assert::assertCount(4, $find['data']);

        $find = $this->criteria([DtoInterface::DTO_CLASS => static::getDtoClass(), ModelInterface::ACTIVE => ActiveModel::ACTIVE, ModelInterface::TRACK => sha1(Track::value())]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find['data']);
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria([DtoInterface::DTO_CLASS => static::getDtoClass(), ModelInterface::ACTIVE => "e"]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey('data', $find);

        $find = $this->criteria([DtoInterface::DTO_CLASS => static::getDtoClass(), ModelInterface::ID => 1000]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey('data', $find);

        $find = $this->criteria([DtoInterface::DTO_CLASS => static::getDtoClass(), ModelInterface::TRACK => crypt(Track::wrong(), 'salt')]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey('data', $find);
    }

    public function actionPut(): void
    {

    }

    public function actionPutUnprocessable(): void
    {

    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankTrack();
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $this->createTracker();
        $this->testResponseStatusCreated();

        $this->createTracker();
        $this->testResponseStatusConflict();

    }

    public function actionDelete(): void
    {
        $testedId = 1;

        $find = $this->assertGet($testedId);

        Assert::assertEquals($find['data']['active'], ActiveModel::ACTIVE);

        $this->delete($testedId);
        $this->testResponseStatusAccepted();

        $delete = $this->assertGet($testedId);

        Assert::assertEquals(ActiveModel::DELETED, $delete['data']['active']);
    }

    public function actionDeleteNotFound(): void
    {
        $response = $this->delete(1000);
        Assert::assertArrayHasKey('data', $response);
        $this->testResponseStatusNotFound();
    }

    public function actionPutNotFound(): void
    {

    }

    public function actionDeleteUnprocessable(): void
    {
        $response = $this->delete('');
        Assert::assertArrayHasKey('data', $response);
        $this->testResponseStatusUnprocessable();
    }

    public function actionGetNotFound(): void
    {
        $response = $this->get(1000);
        Assert::assertArrayHasKey('data', $response);
        $this->testResponseStatusNotFound();
    }

    public function actionGet(): void
    {
        $this->assertGet(2);
    }
//endregion Public

//region SECTION: Getters/Setters
    public static function getDtoClass(): string
    {
        return TrackerApiDto::class;
    }
//endregion Getters/Setters
}