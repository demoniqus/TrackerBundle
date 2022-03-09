<?php


namespace Demoniqus\TrackerBundle\Factory;


use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;

interface TrackerFactoryInterface
{
//region SECTION:Public
    public function create(TrackerApiDtoInterface $dto): TrackerInterface;
//endregion Public
}