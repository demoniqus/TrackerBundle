<?php

namespace Evrinoma\UidBundle\Tests\Functional\Action;


use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UidBundle\Dto\UidApiDto;
use Evrinoma\UidBundle\Tests\Functional\Helper\BaseUidTestTrait;
use Evrinoma\UidBundle\EvrinomaUidBundle;
use Evrinoma\TestUtilsBundle\Action\AbstractServiceTest;
use Evrinoma\UidBundle\Tests\Functional\ValueObject\Uid;
use Evrinoma\UtilsBundle\Model\ActiveModel;
use PHPUnit\Framework\Assert;


class BaseUid extends AbstractServiceTest implements BaseUidTestInterface
{
    use BaseUidTestTrait;

//region SECTION: Fields
    private const API_PREFIX = EvrinomaUidBundle::VENDOR_PREFIX_LC . '/api/' . EvrinomaUidBundle::UID_LC;
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
            "uid"  => crypt(Uid::value(), 'salt'),
            DtoInterface::DTO_CLASS => UidApiDto::class,
        ];
    }

    public function actionPost(): void
    {
        $this->createUid();
        $this->testResponseStatusCreated();
    }

    public function actionCriteria(): void
    {
        $find = $this->criteria(["class" => static::getDtoClass(), "active" => ActiveModel::ACTIVE, "id" => 1]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find['data']);

        $find = $this->criteria(["class" => static::getDtoClass(), "active" => ActiveModel::ACTIVE]);
        $this->testResponseStatusOK();
        Assert::assertCount(4, $find['data']);

        $find = $this->criteria(["class" => static::getDtoClass(), "active" => ActiveModel::ACTIVE, "uid" => sha1(Uid::value())]);
        $this->testResponseStatusOK();
        Assert::assertCount(1, $find['data']);
    }

    public function actionCriteriaNotFound(): void
    {
        $find = $this->criteria(["class" => static::getDtoClass(), "active" => "e"]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey('data', $find);

        $find = $this->criteria(["class" => static::getDtoClass(), "id" => 1000]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey('data', $find);

        $find = $this->criteria(["class" => static::getDtoClass(), "uid" => crypt(Uid::wrong(), 'salt')]);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey('data', $find);
    }

    public function actionPut(): void
    {

    }

    public function actionPutUnprocessable(): void
    {
//        $query = static::getDefault(['id' => '']);
//
//        $this->put($query);
//        $this->testResponseStatusUnprocessable();
//
//        $created = $this->createUid();
//        $this->checkResult($created);
//
//        $query = static::getDefault(['name' => '', 'id' => $created['data']['id']]);
//
//        $this->put($query);
//        $this->testResponseStatusUnprocessable();
//
//        $query = static::getDefault(['type' => '', 'id' => $created['data']['id']]);
//
//        $this->put($query);
//        $this->testResponseStatusUnprocessable();
    }

    public function actionPostUnprocessable(): void
    {
        $this->postWrong();
        $this->testResponseStatusUnprocessable();

        $this->createConstraintBlankUid();
        $this->testResponseStatusUnprocessable();
    }

    public function actionPostDuplicate(): void
    {
        $this->createUid();
        $this->testResponseStatusCreated();

        $this->createUid();
        $this->testResponseStatusConflict();

    }

    public function actionDelete(): void
    {
        $testedId = 1;

        $find = $this->assertGet($testedId);

        Assert::assertContains($find['data']['active'], [ActiveModel::ACTIVE, ActiveModel::ACTIVE]);

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
//        $this->put(static::getDefault(["id" => 1000, "type" => DashedEstimateGenNumberUid::getType(),]));
//        $this->testResponseStatusNotFound();
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
        return UidApiDto::class;
    }
//endregion Getters/Setters
}