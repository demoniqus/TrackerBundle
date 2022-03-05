<?php

namespace Demoniqus\UidBundle\Manager;

use Demoniqus\UidBundle\Exception\UidProxyException;
use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Exception\UidNotFoundException;
use Demoniqus\UidBundle\Model\Uid\UidInterface;
use Demoniqus\UidBundle\Repository\UidQueryRepositoryInterface;
use Evrinoma\UtilsBundle\Rest\RestInterface;
use Evrinoma\UtilsBundle\Rest\RestTrait;

final class QueryManager implements QueryManagerInterface, RestInterface
{
    use RestTrait;

//region SECTION: Fields
    private UidQueryRepositoryInterface $repository;
//endregion Fields

//region SECTION: Constructor
    public function __construct(UidQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param UidApiDtoInterface $dto
     *
     * @return array
     * @throws UidNotFoundException
     */
    public function criteria(UidApiDtoInterface $dto): array
    {
        return $this->repository->findByCriteria($dto);
    }

    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidProxyException
     */
    public function proxy(UidApiDtoInterface $dto): UidInterface
    {
        if ($dto->hasId()) {
            $uid = $this->repository->proxy($dto->getId());
        }
        else {
            throw new UidProxyException("Id value is not set while trying get proxy object");
        }

        return $uid;
    }
//endregion Public

//region SECTION: Getters/Setters
    public function getRestStatus(): int
    {
        return $this->status;
    }

    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidNotFoundException
     */
    public function get(UidApiDtoInterface $dto): UidInterface
    {
        return $this->repository->find($dto->getId());
    }
//endregion Getters/Setters
}