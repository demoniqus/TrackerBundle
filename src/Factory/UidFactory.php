<?php


namespace Demoniqus\UidBundle\Factory;


use Demoniqus\UidBundle\Dto\UidApiDtoInterface;
use Demoniqus\UidBundle\Entity\Uid\BaseUid;
use Demoniqus\UidBundle\Model\Uid\UidInterface;

class UidFactory implements UidFactoryInterface
{
//region SECTION: Fields
    private static string $entity_class = BaseUid::class;
//endregion Fields

//region SECTION: Constructor
    public function __construct(string $entity_class)
    {
        self::$entity_class = $entity_class;
    }
//endregion Constructor

//region SECTION: Public
    public function create(UidApiDtoInterface $dto): UidInterface
    {
        /** @var BaseUid $uid */
        $uid = new self::$entity_class;
        if ($dto->hasUid()) {
            $uid->setUid($dto->getUid());
        }

        $uid
            ->setActiveToActive()
            ->setCreatedAt(new \DateTimeImmutable());

        return $uid;
    }
//endregion Public
}