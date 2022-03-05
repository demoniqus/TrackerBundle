<?php


namespace Demoniqus\UidBundle\Mediator;


use Demoniqus\UidBundle\Exception\UidCannotBeSavedException;
use Demoniqus\UidBundle\Model\Uid\UidInterface;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractCommandMediator;

class CommandMediator extends AbstractCommandMediator implements CommandMediatorInterface
{
//region SECTION: Public
    public function onUpdate(DtoInterface $dto, $entity): UidInterface
    {
        throw new UidCannotBeSavedException('Операция обновления не применима для идентификаторов.');
    }

    public function onDelete(DtoInterface $dto, $entity): void
    {

    }

    public function onCreate(DtoInterface $dto, $entity): UidInterface
    {
        return $entity;
    }
//endregion Public
}