<?php

namespace Evrinoma\UidBundle\Manager;

use Evrinoma\UidBundle\Dto\UidApiDtoInterface;
use Evrinoma\UidBundle\Exception\UidCannotBeRemovedException;
use Evrinoma\UidBundle\Exception\UidInvalidException;
use Evrinoma\UidBundle\Exception\UidNotFoundException;
use Evrinoma\UidBundle\Model\Uid\UidInterface;

interface CommandManagerInterface
{
    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidInvalidException
     */
    public function post(UidApiDtoInterface $dto): UidInterface;

    /**
     * @param UidApiDtoInterface $dto
     *
     * @return UidInterface
     * @throws UidInvalidException
     * @throws UidNotFoundException
     */
    public function put(UidApiDtoInterface $dto): UidInterface;

    /**
     * @param UidApiDtoInterface $dto
     *
     * @throws UidCannotBeRemovedException
     * @throws UidNotFoundException
     */
    public function delete(UidApiDtoInterface $dto): void;
}