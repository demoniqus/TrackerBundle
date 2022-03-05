<?php


namespace Demoniqus\UidBundle\Dto\Preserve;


trait UidApiDtoTrait
{
//region SECTION: Getters/Setters
    public function setActive(string $active): void
    {
        parent::setActive($active);
    }

    public function setId(?int $id): void
    {
        parent::setId($id);
    }

    public function setUid(string $uid): void
    {
        parent::setUid($uid);
    }
//endregion Getters/Setters
}