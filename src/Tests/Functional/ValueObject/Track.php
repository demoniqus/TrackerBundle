<?php


namespace Demoniqus\TrackerBundle\Tests\Functional\ValueObject;


use Evrinoma\TestUtilsBundle\ValueObject\AbstractValueObject;
use Evrinoma\TestUtilsBundle\ValueObject\ValueObjectTest;

final class Track extends AbstractValueObject implements ValueObjectTest
{
//region SECTION: Fields
    protected static string $value = 'some track';
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