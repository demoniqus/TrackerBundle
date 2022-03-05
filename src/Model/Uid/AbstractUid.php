<?php


namespace Demoniqus\UidBundle\Model\Uid;

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="idx_uid", columns={"uid", "active"})
 *     }
 * )
 */
abstract class AbstractUid implements UidInterface
{
    use IdTrait, ActiveTrait, CreateUpdateAtTrait;
//region SECTION: Fields
    /**
     * @var string|null
     *
     * @ORM\Column(name="uid", type="string", length=255, nullable=true)
     */
    protected ?string $uid = null;
//endregion Fields

//region SECTION: Getters/Setters
    public function setUid(string $uid): UidInterface
    {
        $this->uid = $uid;

        return $this;
    }

    public function getUid(): string
    {
        return $this->uid;
    }
//endregion Getters/Setters
}