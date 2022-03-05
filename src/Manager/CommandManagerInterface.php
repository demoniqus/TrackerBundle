<?php

namespace Demoniqus\UidBundle\Manager;

use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Exception\UidCannotBeRemovedException;
use Demoniqus\UidBundle\Exception\UidInvalidException;
use Demoniqus\UidBundle\Exception\UidNotFoundException;
use Demoniqus\UidBundle\Model\Uid\UidInterface;

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