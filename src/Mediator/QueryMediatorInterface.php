<?php


namespace Demoniqus\TrackerBundle\Mediator;

use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Doctrine\ORM\QueryBuilder;


interface QueryMediatorInterface
{
//region SECTION: Public
    public function alias(): string;

    /**
     * @param TrackerApiDtoInterface $dto
     * @param QueryBuilder           $builder
     */
    public function createQuery(TrackerApiDtoInterface $dto, QueryBuilder $builder): void;
//endregion Public
//region SECTION: Getters/Setters
    /**
     * @param TrackerApiDtoInterface $dto
     * @param QueryBuilder           $builder
     * @return array
     */
    public function getResult(TrackerApiDtoInterface $dto, QueryBuilder $builder): array;
//endregion Getters/Setters
}