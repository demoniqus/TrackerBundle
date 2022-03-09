<?php


namespace Demoniqus\TrackerBundle\Dto\Preserve;


interface TrackerApiDtoInterface
{
//region SECTION: Getters/Setters
    public function setActive(string $active): void;

    public function setId(?int $id): void;

    public function setTrack(string $track): void;
//endregion Getters/Setters
}