<?php

namespace Demoniqus\TrackerBundle\Manager;

use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeRemovedException;
use Demoniqus\TrackerBundle\Exception\TrackerInvalidException;
use Demoniqus\TrackerBundle\Exception\TrackerNotFoundException;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;

interface CommandManagerInterface
{
    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return TrackerInterface
     * @throws TrackerInvalidException
     */
    public function post(TrackerApiDtoInterface $dto): TrackerInterface;

    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return TrackerInterface
     * @throws TrackerInvalidException
     * @throws TrackerNotFoundException
     */
    public function put(TrackerApiDtoInterface $dto): TrackerInterface;

    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @throws TrackerCannotBeRemovedException
     * @throws TrackerNotFoundException
     */
    public function delete(TrackerApiDtoInterface $dto): void;
}