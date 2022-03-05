<?php

namespace Evrinoma\UidBundle\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Evrinoma\UidBundle\Entity\Uid\BaseUid;
use Evrinoma\UidBundle\Tests\Functional\ValueObject\Uid;

class UidFixtures extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
//region SECTION: Private
    /**
     * @return string[][]
     */
    private function getData(): array
    {
        return [
            [
                'active' => 'a', 'created_at' => '2010-01-01 00:00:00',
                'uid'   => md5(Uid::value()),
            ],
            [
                'active' => 'a', 'created_at' => '2010-01-02 01:00:00',
                'uid'   => Uid::value(),
            ],
            [
                'active' => 'a', 'created_at' => '2010-01-03 02:00:00',
                'uid'   => 123456789,
            ],
            [
                'active' => 'a', 'created_at' => '2010-01-04 03:00:00',
                'uid'   => sha1(Uid::value()),
            ],
        ];
    }

    private function create(ObjectManager $manager): void
    {
        $short = (new \ReflectionClass(BaseUid::class))->getShortName() . "_";
        $i = 0;

        foreach ($this->getData() as $record) {
            $entity = new BaseUid();
            $entity
                ->setUid($record['uid'])
                ->setCreatedAt(new \DateTimeImmutable($record['created_at']))
                ->setActive($record['active']);

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
        return [FixtureInterface::UID_FIXTURES,];
    }
//endregion Getters/Setters
}