<?php


namespace Demoniqus\TrackerBundle\Dto;


use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ActiveInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdInterface;

interface TrackerApiDtoInterface extends DtoInterface, IdInterface, ActiveInterface
{
//region SECTION: Getters/Setters
    public function getTrack(): string;

    public function hasTrack(): bool;
//endregion Getters/Setters
}