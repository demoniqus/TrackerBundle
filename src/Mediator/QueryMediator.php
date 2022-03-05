<?php


namespace Demoniqus\UidBundle\Mediator;


use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Repository\AliasInterface;
use Doctrine\ORM\QueryBuilder;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\UtilsBundle\Mediator\AbstractQueryMediator;

class QueryMediator extends AbstractQueryMediator implements QueryMediatorInterface
{
//region SECTION: Fields
    protected static string $alias = AliasInterface::UID;
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

        /** @var $dto UidApiDtoInterface */
        if ($dto->hasId()) {
            $builder
                ->andWhere($alias . '.id = :id')
                ->setParameter('id', $dto->getId());
        }

        if ($dto->hasUid()) {
            $builder
                ->andWhere($alias . '.uid = :uid')
                ->setParameter('uid', $dto->getUid());
        }

        if ($dto->hasActive()) {
            $builder
                ->andWhere($alias . '.active = :active')
                ->setParameter('active', $dto->getActive());
        }
    }
//endregion Public
}