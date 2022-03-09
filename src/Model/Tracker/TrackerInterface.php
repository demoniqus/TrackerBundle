<?php


namespace Demoniqus\TrackerBundle\Model\Tracker;


use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;

interface TrackerInterface extends IdInterface, ActiveInterface
{
//region SECTION: Getters/Setters
    public function getTrack(): string;

    public function setTrack(string $track): TrackerInterface;
//endregion Getters/Setters
}