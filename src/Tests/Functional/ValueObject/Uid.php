<?php


namespace Demoniqus\UidBundle\Tests\Functional\ValueObject;


use Evrinoma\TestUtilsBundle\ValueObject\AbstractValueObject;
use Evrinoma\TestUtilsBundle\ValueObject\ValueObjectTest;

final class Uid extends AbstractValueObject implements ValueObjectTest
{
//region SECTION: Fields
    protected static string $value = 'some uid';
//endregion Fields

//region SECTION: Getters/Setters
    public static function wrong(): string
    {
        return strrev(self::$value);
    }

    public static function value(): string
    {
        return self::$value;
    }
//endregion Public
}