<?php


namespace Demoniqus\TrackerBundle\Repository;


use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeSavedException;
use Demoniqus\TrackerBundle\Exception\TrackerNotFoundException;
use Demoniqus\TrackerBundle\Exception\TrackerProxyException;
use Demoniqus\TrackerBundle\Mediator\QueryMediatorInterface;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;

class TrackerRepository extends ServiceEntityRepository implements TrackerRepositoryInterface
{
//region SECTION: Fields
    private QueryMediatorInterface $mediator;
//endregion Fields

//region SECTION: Constructor
    /**
     * @param ManagerRegistry        $registry
     * @param string                 $entityClass
     * @param QueryMediatorInterface $mediator
     */
    public function __construct(ManagerRegistry $registry, string $entityClass, QueryMediatorInterface $mediator)
    {
        parent::__construct($registry, $entityClass);
        $this->mediator = $mediator;
    }
//endregion Constructor

    //region SECTION: Public

    /**
     * @param TrackerInterface $track
     *
     * @return bool
     * @throws TrackerCannotBeSavedException
     * @throws ORMException
     */
    public function save(TrackerInterface $track): bool
    {
        try {
            $this->getEntityManager()->persist($track);
        } catch (ORMInvalidArgumentException $e) {
            throw new TrackerCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param TrackerInterface $track
     *
     * @return bool
     */
    public function remove(TrackerInterface $track): bool
    {
        $track->setActiveToDelete();

        return true;
    }

    /**
     * @param string $id
     *
     * @return TrackerInterface
     * @throws TrackerProxyException
     * @throws ORMException
     */
    public function proxy(string $id): TrackerInterface
    {
        $em = $this->getEntityManager();

        $tracker = $em->getReference($this->getEntityName(), $id);

        if (!$em->contains($tracker)) {
            throw new TrackerProxyException("Proxy doesn't exist with $id");
        }

        return $tracker;
    }
//endregion Public

//region SECTION: Find Filters Repository
    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return array
     * @throws TrackerNotFoundException
     */
    public function findByCriteria(TrackerApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilder($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $strategies = $this->mediator->getResult($dto, $builder);

        if (count($strategies) === 0) {
            throw new TrackerNotFoundException("Cannot find tracker by findByCriteria");
        }

        return $strategies;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     * @throws TrackerNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): TrackerInterface
    {
        /** @var TrackerInterface $tracker */
        $tracker = parent::find($id);

        if ($tracker === null) {
            throw new TrackerNotFoundException("Cannot find tracker with id $id");
        }

        return $tracker;
    }
//endregion Find Filters Repository
}