<?php


namespace Demoniqus\UidBundle\Repository;


use Demoniqus\UidBundle\Exception\UidCannotBeRemovedException;
use Demoniqus\UidBundle\Exception\UidCannotBeSavedException;
use Demoniqus\UidBundle\Model\Uid\UidInterface;

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