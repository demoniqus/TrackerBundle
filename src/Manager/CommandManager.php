<?php

namespace Demoniqus\TrackerBundle\Manager;

use Demoniqus\TrackerBundle\Exception\TrackerCannotBeCreatedException;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeRemovedException;
use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Exception\TrackerCannotBeSavedException;
use Demoniqus\TrackerBundle\Exception\TrackerInvalidException;
use Demoniqus\TrackerBundle\Exception\TrackerNotFoundException;
use Demoniqus\TrackerBundle\Factory\TrackerFactoryInterface;
use Demoniqus\TrackerBundle\Mediator\CommandMediatorInterface;
use Demoniqus\TrackerBundle\Model\Tracker\TrackerInterface;
use Demoniqus\TrackerBundle\Repository\TrackerCommandRepositoryInterface;
use Evrinoma\UtilsBundle\Rest\RestInterface;
use Evrinoma\UtilsBundle\Rest\RestTrait;
use Evrinoma\UtilsBundle\Validator\ValidatorInterface;

final class CommandManager implements CommandManagerInterface, RestInterface
{
    use RestTrait;

//region SECTION: Fields
    private TrackerCommandRepositoryInterface $repository;
    private ValidatorInterface                $validator;
    private TrackerFactoryInterface           $factory;
    private CommandMediatorInterface          $mediator;
//endregion Fields

//region SECTION: Constructor
    /**
     * @param ValidatorInterface                $validator
     * @param TrackerCommandRepositoryInterface $repository
     * @param TrackerFactoryInterface           $factory
     * @param CommandMediatorInterface          $mediator
     */
    public function __construct(
        ValidatorInterface                $validator,
        TrackerCommandRepositoryInterface $repository,
        TrackerFactoryInterface           $factory,
        CommandMediatorInterface          $mediator
    ) {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->mediator = $mediator;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return TrackerInterface
     * @throws TrackerInvalidException|TrackerCannotBeCreatedException
     * @throws TrackerCannotBeSavedException
     */
    public function post(TrackerApiDtoInterface $dto): TrackerInterface
    {
        $sid = $this->factory->create($dto);

        $this->mediator->onCreate($dto, $sid);

        $errors = $this->validator->validate($sid);

        if (count($errors) > 0) {

            $errorsString = (string)$errors;

            throw new TrackerInvalidException($errorsString);
        }

        $this->repository->save($sid);

        return $sid;
    }

    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @return TrackerInterface
     * @throws TrackerInvalidException
     * @throws TrackerNotFoundException|TrackerCannotBeSavedException
     */
    public function put(TrackerApiDtoInterface $dto): TrackerInterface
    {
        $sid = $this->repository->find($dto->getId());

        $sid
            ->setTrack($dto->getTrack())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setActive($dto->getActive());


        $this->mediator->onUpdate($dto, $sid);

        $errors = $this->validator->validate($sid);

        if (count($errors) > 0) {

            $errorsString = (string)$errors;

            throw new TrackerInvalidException($errorsString);
        }

        $this->repository->save($sid);

        return $sid;
    }

    /**
     * @param TrackerApiDtoInterface $dto
     *
     * @throws TrackerCannotBeRemovedException
     * @throws TrackerNotFoundException
     */
    public function delete(TrackerApiDtoInterface $dto): void
    {
        $sid = $this->repository->find($dto->getId());

        $this->mediator->onDelete($dto, $sid);

        $this->repository->remove($sid);
    }
//endregion Public

//region SECTION: Getters/Setters
    public function getRestStatus(): int
    {
        return $this->status;
    }
//endregion Getters/Setters
}