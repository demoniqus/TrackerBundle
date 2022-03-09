<?php


namespace Demoniqus\TrackerBundle\Mediator;


use Demoniqus\TrackerBundle\Dto\TrackerApiDtoInterface;
use Demoniqus\TrackerBundle\Model\ModelInterface;
use Demoniqus\TrackerBundle\Repository\AliasInterface;
use Doctrine\ORM\QueryBuilder;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractQueryMediator;

class QueryMediator extends AbstractQueryMediator implements QueryMediatorInterface
{
//region SECTION: Fields
    protected static string $alias = AliasInterface::TRACKER;
//endregion Fields

//region SECTION: Public
    /**
     * @param DtoInterface $dto
     * @param QueryBuilder $builder
     *
     * @return mixed
     */
    public function createQuery(DtoInterface $dto, QueryBuilder $builder): void
    {
        $alias = $this->alias();

        /** @var $dto TrackerApiDtoInterface */
        if ($dto->hasId()) {
            $builder
                ->andWhere($alias . '.' . ModelInterface::ID .' = :id')
                ->setParameter('id', $dto->getId());
        }

        if ($dto->hasTrack()) {
            $builder
                ->andWhere($alias . '.' . ModelInterface::TRACK . ' = :track')
                ->setParameter('track', $dto->getTrack());
        }

        if ($dto->hasActive()) {
            $builder
                ->andWhere($alias . '.' . ModelInterface::ACTIVE . ' = :active')
                ->setParameter('active', $dto->getActive());
        }
    }
//endregion Public
}