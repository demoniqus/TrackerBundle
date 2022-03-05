<?php


namespace Evrinoma\UidBundle\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\Persistence\ManagerRegistry;
use Evrinoma\UidBundle\Dto\UidApiDtoInterface;
use Evrinoma\UidBundle\Exception\UidCannotBeSavedException;
use Evrinoma\UidBundle\Exception\UidNotFoundException;
use Evrinoma\UidBundle\Exception\UidProxyException;
use Evrinoma\UidBundle\Mediator\QueryMediatorInterface;
use Evrinoma\UidBundle\Model\Uid\UidInterface;

class UidRepository extends ServiceEntityRepository implements UidRepositoryInterface
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
     * @param UidInterface $uid
     *
     * @return bool
     * @throws UidCannotBeSavedException
     * @throws ORMException
     */
    public function save(UidInterface $uid): bool
    {
        try {
            $this->getEntityManager()->persist($uid);
        } catch (ORMInvalidArgumentException $e) {
            throw new UidCannotBeSavedException($e->getMessage());
        }

        return true;
    }

    /**
     * @param UidInterface $uid
     *
     * @return bool
     */
    public function remove(UidInterface $uid): bool
    {
        $uid->setActiveToDelete();

        return true;
    }

    /**
     * @param string $id
     *
     * @return UidInterface
     * @throws UidProxyException
     * @throws ORMException
     */
    public function proxy(string $id): UidInterface
    {
        $em = $this->getEntityManager();

        $uid = $em->getReference($this->getEntityName(), $id);

        if (!$em->contains($uid)) {
            throw new UidProxyException("Proxy doesn't exist with $id");
        }

        return $uid;
    }
//endregion Public

//region SECTION: Find Filters Repository
    /**
     * @param UidApiDtoInterface $dto
     *
     * @return array
     * @throws UidNotFoundException
     */
    public function findByCriteria(UidApiDtoInterface $dto): array
    {
        $builder = $this->createQueryBuilder($this->mediator->alias());

        $this->mediator->createQuery($dto, $builder);

        $strategies = $this->mediator->getResult($dto, $builder);

        if (count($strategies) === 0) {
            throw new UidNotFoundException("Cannot find uid by findByCriteria");
        }

        return $strategies;
    }

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return mixed
     * @throws UidNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): UidInterface
    {
        /** @var UidInterface $uid */
        $uid = parent::find($id);

        if ($uid === null) {
            throw new UidNotFoundException("Cannot find uid with id $id");
        }

        return $uid;
    }
//endregion Find Filters Repository
}