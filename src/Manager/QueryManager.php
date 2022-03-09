<?php

namespace Demoniqus\TrackerBundle\Manager;

use Demoniqus\TrackerBundle\Exception\TrackerProxyException;
use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Exception\TrackerNotFoundException;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;
use Demoniqus\TrackerBundle\Repository\TrackerQueryRepositoryInterface;
use Evrinoma\UtilsBundle\Rest\RestInterface;
use Evrinoma\UtilsBundle\Rest\RestTrait;

final class QueryManager implements QueryManagerInterface, RestInterface
{
    use RestTrait;

//region SECTION: Fields
    private TrackerQueryRepositoryInterface $repository;
//endregion Fields

//region SECTION: Constructor
    public function __construct(TrackerQueryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return array
     * @throws TrackerNotFoundException
     */
    public function criteria(TrackerApiDtoInterface $dto): array
    {
        return $this->repository->findByCriteria($dto);
    }

    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return TrackerInterface
     * @throws TrackerProxyException
     */
    public function proxy(TrackerApiDtoInterface $dto): TrackerInterface
    {
        if ($dto->hasId()) {
            $uid = $this->repository->proxy($dto->getId());
        }
        else {
            throw new TrackerProxyException("Id value is not set while trying get proxy object");
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
     * @param TrackerApiDtoInterface $dto
     *
     * @return TrackerInterface
     * @throws TrackerNotFoundException
     */
    public function get(TrackerApiDtoInterface $dto): TrackerInterface
    {
        return $this->repository->find($dto->getId());
    }
//endregion Getters/Setters
}