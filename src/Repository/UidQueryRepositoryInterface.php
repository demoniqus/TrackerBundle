<?php


namespace Evrinoma\UidBundle\Repository;


use Doctrine\ORM\Exception\ORMException;
use Evrinoma\UidBundle\Dto\UidApiDtoInterface;
use Evrinoma\UidBundle\Exception\UidNotFoundException;
use Evrinoma\UidBundle\Exception\UidProxyException;
use Evrinoma\UidBundle\Model\Uid\UidInterface;

interface UidQueryRepositoryInterface
{
    /**
     * @param UidApiDtoInterface $dto
     *
     * @return array
     * @throws UidNotFoundException
     */
    public function findByCriteria(UidApiDtoInterface $dto): array;

    /**
     * @param      $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return UidInterface
     * @throws UidNotFoundException
     */
    public function find($id, $lockMode = null, $lockVersion = null): UidInterface;

    /**
     * @param string $id
     *
     * @return UidInterface
     * @throws UidProxyException
     * @throws ORMException
     */
    public function proxy(string $id): UidInterface;
}