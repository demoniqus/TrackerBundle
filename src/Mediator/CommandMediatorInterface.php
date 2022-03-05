<?php


namespace Demoniqus\UidBundle\Mediator;

use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Exception\UidCannotBeCreatedException;
use Demoniqus\UidBundle\Exception\UidCannotBeRemovedException;
use Demoniqus\UidBundle\Exception\UidCannotBeSavedException;
use Demoniqus\UidBundle\Model\Uid\UidInterface;


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