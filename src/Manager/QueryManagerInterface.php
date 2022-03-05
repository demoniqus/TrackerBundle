<?php

namespace Evrinoma\UidBundle\Manager;

use Evrinoma\UidBundle\Dto\UidApiDtoInterface;
use Evrinoma\UidBundle\Exception\UidNotFoundException;
use Evrinoma\UidBundle\Exception\UidProxyException;
use Evrinoma\UidBundle\Model\Uid\UidInterface;

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