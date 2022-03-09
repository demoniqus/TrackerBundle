<?php


namespace Demoniqus\TrackerBundle\Repository;


use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Exception\TrackerNotFoundException;
use Demoniqus\TrackerBundle\Exception\TrackerProxyException;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;
use Doctrine\ORM\Exception\ORMException;

interface TrackerQueryRepositoryInterface
{
    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return array
     * @throws TrackerNotFoundException
     */
    public function findByCriteria(TrackerApiDtoInterface $dto): array;

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return TrackerInterface
     * @throws TrackerNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): TrackerInterface;

    /**
     * @param string $id
     *
     * @return TrackerInterface
     * @throws TrackerProxyException
     * @throws ORMException
     */
    public function proxy(string $id): TrackerInterface;
}