<?php

namespace Demoniqus\TrackerBundle\Manager;

use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Exception\TrackerNotFoundException;
use Demoniqus\TrackerBundle\Exception\TrackerProxyException;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;

interface QueryManagerInterface
{
    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return array
     * @throws TrackerNotFoundException
     */
    public function criteria(TrackerApiDtoInterface $dto): array;
//endregion Public

//region SECTION: Getters/Setters
    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return TrackerInterface
     * @throws TrackerNotFoundException
     */
    public function get(TrackerApiDtoInterface $dto): TrackerInterface;

    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return TrackerInterface
     * @throws TrackerProxyException
     */
    public function proxy(TrackerApiDtoInterface $dto): TrackerInterface;
}