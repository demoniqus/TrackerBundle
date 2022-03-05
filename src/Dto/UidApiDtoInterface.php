<?php


namespace Evrinoma\UidBundle\Dto;


use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdInterface;

interface UidApiDtoInterface extends DtoInterface, IdInterface, ActiveInterface
{
//region SECTION: Getters/Setters
    public function getUid(): string;

    public function hasUid(): bool;
//endregion Getters/Setters
}