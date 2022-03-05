<?php


namespace Demoniqus\UidBundle\Repository;


use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Exception\UidNotFoundException;
use Demoniqus\UidBundle\Exception\UidProxyException;
use Demoniqus\UidBundle\Model\Uid\UidInterface;
use Doctrine\ORM\Exception\ORMException;

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