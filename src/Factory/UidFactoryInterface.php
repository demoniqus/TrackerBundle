<?php


namespace Evrinoma\UidBundle\Factory;


use Evrinoma\UidBundle\Dto\UidApiDtoInterface;
use Evrinoma\UidBundle\Model\Uid\UidInterface;

interface UidFactoryInterface
{
//region SECTION:Public
    public function create(UidApiDtoInterface $dto): UidInterface;
//endregion Public
}