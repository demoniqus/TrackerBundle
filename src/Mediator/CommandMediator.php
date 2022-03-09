<?php


namespace Demoniqus\TrackerBundle\Mediator;


use Demoniqus\TrackerBundle\Exception\TrackerCannotBeSavedException;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
//region SECTION: Public
    public function onUpdate(DtoInterface $dto, $entity): TrackerInterface
    {
        throw new TrackerCannotBeSavedException('Updating is not allowed to Tracker.');
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {

    }

    public function onCreate(DtoInterface $dto, $entity): TrackerInterface
    {
        return $entity;
    }
//endregion Public
}