<?php


namespace Demoniqus\TrackerBundle\Dto\Preserve;


trait TrackerApiDtoTrait
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

    public function setTrack(string $track): void
    {
        parent::setTrack($track);
    }
//endregion Getters/Setters
}