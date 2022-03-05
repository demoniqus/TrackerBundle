<?php


namespace Evrinoma\UidBundle\Repository;


use Evrinoma\UidBundle\Exception\UidCannotBeRemovedException;
use Evrinoma\UidBundle\Exception\UidCannotBeSavedException;
use Evrinoma\UidBundle\Model\Uid\UidInterface;

interface UidCommandRepositoryInterface
{
//region SECTION:Public
    /**
     * @param UidInterface $uid
     * @return bool
     * @throws UidCannotBeSavedException
     */
    public function save(UidInterface $uid): bool;

    /**
     * @param UidInterface $uid
     * @return bool
     * @throws UidCannotBeRemovedException
     */
    public function remove(UidInterface $uid): bool;
//endregion Public
}