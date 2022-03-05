<?php

namespace Demoniqus\UidBundle\Manager;

use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Exception\UidNotFoundException;
use Demoniqus\UidBundle\Exception\UidProxyException;
use Demoniqus\UidBundle\Model\Uid\UidInterface;

interface QueryManagerInterface
{
    /**
     * @param UidApiDtoInterface $dto
     *
     * @return array
     * @throws UidNotFoundException
     */
    public function criteria(UidApiDtoInterface $dto): array;
//endregion Public

//region SECTION: Getters/Setters
    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidNotFoundException
     */
    public function get(UidApiDtoInterface $dto): UidInterface;

    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidProxyException
     */
    public function proxy(UidApiDtoInterface $dto): UidInterface;
}