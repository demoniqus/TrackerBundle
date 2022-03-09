<?php


namespace Demoniqus\TrackerBundle\Factory;


use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Entity\Tracker\BaseTracker;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;

class TrackerFactory implements TrackerFactoryInterface
{
//region SECTION: Fields
    private static string $entity_class = BaseTracker::class;
//endregion Fields

//region SECTION: Constructor
    public function __construct(string $entity_class)
    {
        self::$entity_class = $entity_class;
    }
//endregion Constructor

//region SECTION: Public
    public function create(TrackerApiDtoInterface $dto): TrackerInterface
    {
        /** @var BaseTracker $tracker */
        $tracker = new self::$entity_class;
        if ($dto->hasTrack()) {
            $tracker->setTrack($dto->getTrack());
        }

        $tracker
            ->setActiveToActive()
            ->setCreatedAt(new \DateTimeImmutable());

        return $tracker;
    }
//endregion Public
}