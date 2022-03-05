<?php


namespace Demoniqus\UidBundle\Factory;


use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Model\Uid\UidInterface;

interface UidFactoryInterface
{
//region SECTION:Public
    public function create(UidApiDtoInterface $dto): UidInterface;
//endregion Public
}