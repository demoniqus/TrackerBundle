<?php

namespace Demoniqus\TrackerBundle\Fixtures;

use Demoniqus\TrackerBundle\Model\ModelInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Demoniqus\TrackerBundle\Entity\Tracker\BaseTracker;
use Demoniqus\TrackerBundle\Tests\Functional\ValueObject\Track;
use Evrinoma\UtilsBundle\Model\ActiveModel;

class TrackerFixtures extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
//region SECTION: Private
    /**
     * @return string[][]
     */
    private function getData(): array
    {
        return [
            [
                ModelInterface::ACTIVE => ActiveModel::ACTIVE,
                ModelInterface::TRACK => md5(Track::value()),
                'created_at' => '2010-01-01 00:00:00',
            ],
            [
                ModelInterface::ACTIVE => ActiveModel::ACTIVE,
                ModelInterface::TRACK  => Track::value(),
                'created_at' => '2010-01-02 01:00:00',
            ],
            [
                ModelInterface::ACTIVE => ActiveModel::ACTIVE,
                ModelInterface::TRACK => 123456789,
                'created_at' => '2010-01-03 02:00:00',
            ],
            [
                ModelInterface::ACTIVE => ActiveModel::ACTIVE,
                ModelInterface::TRACK => sha1(Track::value()),
                'created_at' => '2010-01-04 03:00:00',
            ],
        ];
    }

    private function create(ObjectManager $manager): void
    {
        $short = (new \ReflectionClass(BaseTracker::class))->getShortName() . "_";
        $i = 0;

        foreach ($this->getData() as $record) {
            $entity = new BaseTracker();
            $entity
                ->setTrack($record[ModelInterface::TRACK])
                ->setCreatedAt(new \DateTimeImmutable($record['created_at']))
                ->setActive($record[ModelInterface::ACTIVE]);

            $this->addReference($short . $i, $entity);
            $manager->persist($entity);
            $i++;
        }
    }
//endregion Private

//region SECTION: Public
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->create($manager);

        $manager->flush();
    }
//endregion Public

//region SECTION: Getters/Setters
    public function getOrder(): int
    {
        return 0;
    }

    public static function getGroups(): array
    {
        return [FixtureInterface::TRACKER_FIXTURES,];
    }
//endregion Getters/Setters
}