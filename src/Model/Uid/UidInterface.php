<?php


namespace Evrinoma\UidBundle\Model\Uid;


use Evrinoma\UtilsBundle\Entity\ActiveInterface;
use Evrinoma\UtilsBundle\Entity\IdInterface;

interface UidInterface extends IdInterface, ActiveInterface
{
//region SECTION: Getters/Setters
    public function getUid(): string;

    public function setUid(string $uid): UidInterface;
//endregion Getters/Setters
}