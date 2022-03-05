<?php

namespace Evrinoma\UidBundle\Manager;

use Evrinoma\UidBundle\Exception\UidProxyException;
use Evrinoma\UidBundle\Dto\UidApiDtoInterface;
use Evrinoma\UidBundle\Exception\UidNotFoundException;
use Evrinoma\UidBundle\Model\Uid\UidInterface;
use Evrinoma\UidBundle\Repository\UidQueryRepositoryInterface;
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
        try {
            $uid = $this->repository->findByCriteria($dto);
        } catch (UidNotFoundException $e) {
            throw $e;
        }

        return $uid;
    }

    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidProxyException
     */
    public function proxy(UidApiDtoInterface $dto): UidInterface
    {
        try {
            if ($dto->hasId()) {
                $uid = $this->repository->proxy($dto->getId());
            }
            else {
                throw new UidProxyException("Id value is not set while trying get proxy object");
            }
        } catch (UidProxyException $e) {
            throw $e;
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
        try {
            $uid = $this->repository->find($dto->getId());
        } catch (UidNotFoundException $e) {
            throw $e;
        }

        return $uid;
    }
//endregion Getters/Setters
}