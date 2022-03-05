<?php


namespace Evrinoma\UidBundle\Mediator;

use Evrinoma\UidBundle\Dto\UidApiDtoInterface;
use Evrinoma\UidBundle\Exception\UidCannotBeCreatedException;
use Evrinoma\UidBundle\Exception\UidCannotBeRemovedException;
use Evrinoma\UidBundle\Exception\UidCannotBeSavedException;
use Evrinoma\UidBundle\Model\Uid\UidInterface;


interface CommandMediatorInterface
{
//region SECTION:Public
    /**
     * @param UidApiDtoInterface $dto
     * @param UidInterface       $entity
     *
     * @return UidInterface
     * @throws UidCannotBeSavedException
     */
    public function onUpdate(UidApiDtoInterface $dto, UidInterface $entity): UidInterface;

    /**
     * @param UidApiDtoInterface $dto
     * @param UidInterface       $entity
     *
     * @throws UidCannotBeRemovedException
     */
    public function onDelete(UidApiDtoInterface $dto, UidInterface $entity): void;

    /**
     * @param UidApiDtoInterface $dto
     * @param UidInterface       $entity
     *
     * @return UidInterface
     * @throws UidCannotBeCreatedException
     */
    public function onCreate(UidApiDtoInterface $dto, UidInterface $entity): UidInterface;
//endregion Public
}