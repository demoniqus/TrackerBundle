<?php


namespace Demoniqus\TrackerBundle\Mediator;

use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeCreatedException;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeRemovedException;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeSavedException;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;


interface CommandMediatorInterface
{
//region SECTION:Public
    /**
     * @param TrackerApiDtoInterface $dto
     * @param TrackerInterface       $entity
     *
     * @return TrackerInterface
     * @throws TrackerCannotBeSavedException
     */
    public function onUpdate(TrackerApiDtoInterface $dto, TrackerInterface $entity): TrackerInterface;

    /**
     * @param TrackerApiDtoInterface $dto
     * @param TrackerInterface       $entity
     *
     * @throws TrackerCannotBeRemovedException
     */
    public function onDelete(TrackerApiDtoInterface $dto, TrackerInterface $entity): void;

    /**
     * @param TrackerApiDtoInterface $dto
     * @param TrackerInterface       $entity
     *
     * @return TrackerInterface
     * @throws TrackerCannotBeCreatedException
     */
    public function onCreate(TrackerApiDtoInterface $dto, TrackerInterface $entity): TrackerInterface;
//endregion Public
}