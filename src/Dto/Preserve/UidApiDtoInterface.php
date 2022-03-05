<?php


namespace Evrinoma\UidBundle\Dto\Preserve;


interface UidApiDtoInterface
{
//region SECTION:Public

//endregion Public
//region SECTION: Getters/Setters
    public function setActive(string $active): void;

    public function setId(?int $id): void;

    public function setUid(string $uid): void;
//endregion Getters/Setters
}