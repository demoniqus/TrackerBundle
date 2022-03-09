<?php


namespace Demoniqus\TrackerBundle\Model\Tracker;

use Doctrine\ORM\Mapping as ORM;
use Evrinoma\UtilsBundle\Entity\ActiveTrait;
use Evrinoma\UtilsBundle\Entity\CreateUpdateAtTrait;
use Evrinoma\UtilsBundle\Entity\IdTrait;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="idx_track", columns={"track", "active"})
 *     }
 * )
 */
abstract class AbstractTracker implements TrackerInterface
{
    use IdTrait, ActiveTrait, CreateUpdateAtTrait;
//region SECTION: Fields
    /**
     * @var string|null
     *
     * @ORM\Column(name="track", type="string", length=255, nullable=true)
     */
    protected ?string $track = null;
//endregion Fields

//region SECTION: Getters/Setters
    public function setTrack(string $track): TrackerInterface
    {
        $this->track = $track;

        return $this;
    }

    public function getTrack(): string
    {
        return $this->track;
    }
//endregion Getters/Setters
}