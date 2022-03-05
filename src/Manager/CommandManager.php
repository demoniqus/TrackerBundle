<?php

namespace Evrinoma\UidBundle\Manager;

use Evrinoma\UidBundle\Exception\UidCannotBeCreatedException;
use Evrinoma\UidBundle\Exception\UidCannotBeRemovedException;
use Evrinoma\UidBundle\Dto\UidApiDtoInterface;
use Evrinoma\UidBundle\Exception\UidCannotBeSavedException;
use Evrinoma\UidBundle\Exception\UidInvalidException;
use Evrinoma\UidBundle\Exception\UidNotFoundException;
use Evrinoma\UidBundle\Factory\UidFactoryInterface;
use Evrinoma\UidBundle\Mediator\CommandMediatorInterface;
use Evrinoma\UidBundle\Model\Uid\UidInterface;
use Evrinoma\UidBundle\Repository\UidCommandRepositoryInterface;
use Evrinoma\UtilsBundle\Rest\RestInterface;
use Evrinoma\UtilsBundle\Rest\RestTrait;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface, RestInterface
{
    use RestTrait;

//region SECTION: Fields
    private UidCommandRepositoryInterface $repository;
    private ValidatorInterface                 $validator;
    private UidFactoryInterface           $factory;
    private CommandMediatorInterface           $mediator;
//endregion Fields

//region SECTION: Constructor
    /**
     * @param ValidatorInterface                 $validator
     * @param UidCommandRepositoryInterface $repository
     * @param UidFactoryInterface           $factory
     * @param CommandMediatorInterface           $mediator
     */
    public function __construct(
        ValidatorInterface                 $validator,
        UidCommandRepositoryInterface $repository,
        UidFactoryInterface           $factory,
        CommandMediatorInterface           $mediator
    ) {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidInvalidException|UidCannotBeCreatedException
     * @throws UidCannotBeSavedException
     */
    public function post(UidApiDtoInterface $dto): UidInterface
    {
        $sid = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $sid);

        $errors = $this->validator->validate($sid);

        if (count($errors) > 0) {

            $errorsString = (string)$errors;

            throw new UidInvalidException($errorsString);
        }

        $this->repository->save($sid);

        return $sid;
    }

    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidInvalidException
     * @throws UidNotFoundException|UidCannotBeSavedException
     */
    public function put(UidApiDtoInterface $dto): UidInterface
    {
        try {
            $sid = $this->repository->find($dto->getId());
        } catch (UidNotFoundException $e) {
            throw $e;
        }

        $sid
            ->setUid($dto->getUid())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setActive($dto->getActive());


        $this->mediator->onUpdate($dto, $sid);

        $errors = $this->validator->validate($sid);

        if (count($errors) > 0) {

            $errorsString = (string)$errors;

            throw new UidInvalidException($errorsString);
        }

        $this->repository->save($sid);

        return $sid;
    }

    /**
     * @param UidApiDtoInterface $dto
     *
     * @throws UidCannotBeRemovedException
     * @throws UidNotFoundException
     */
    public function delete(UidApiDtoInterface $dto): void
    {
        try {
            $sid = $this->repository->find($dto->getId());
        } catch (UidNotFoundException $e) {
            throw $e;
        }
        $this->mediator->onDelete($dto, $sid);
        try {
            $this->repository->remove($sid);
        } catch (UidCannotBeRemovedException $e) {
            throw $e;
        }
    }
//endregion Public

//region SECTION: Getters/Setters
    public function getRestStatus(): int
    {
        return $this->status;
    }
//endregion Getters/Setters
}