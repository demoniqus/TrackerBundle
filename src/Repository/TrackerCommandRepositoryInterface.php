<?php


namespace Demoniqus\TrackerBundle\Repository;


use Demoniqus\TrackerBundle\Exception\TrackerCannotBeRemovedException;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeSavedException;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;

interface TrackerCommandRepositoryInterface
{
//region SECTION:Public
    /**
     * @param TrackerInterface $track
     * @return bool
     * @throws TrackerCannotBeSavedException
     */
    public function save(TrackerInterface $track): bool;

    /**
     * @param TrackerInterface $track
     * @return bool
     * @throws TrackerCannotBeRemovedException
     */
    public function remove(TrackerInterface $track): bool;
//endregion Public
}