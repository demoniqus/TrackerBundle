<?php


namespace Demoniqus\UidBundle\Mediator;

use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Doctrine\ORM\QueryBuilder;


interface QueryMediatorInterface
{
//region SECTION: Public
    public function alias(): string;

    /**
     * @param UidApiDtoInterface $dto
     * @param QueryBuilder            $builder
     */
    public function createQuery(UidApiDtoInterface $dto, QueryBuilder $builder): void;
//endregion Public
//region SECTION: Getters/Setters
    /**
     * @param UidApiDtoInterface $dto
     * @param QueryBuilder            $builder
     * @return array
     */
    public function getResult(UidApiDtoInterface $dto, QueryBuilder $builder): array;
//endregion Getters/Setters
}