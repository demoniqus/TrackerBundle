<?php


namespace Demoniqus\TrackerBundle\Dto;


use Demoniqus\TrackerBundle\Model\ModelInterface;
use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdTrait;
use Symfony\Component\HttpFoundation\Request;

class TrackerApiDto extends AbstractDto implements TrackerApiDtoInterface
{
    use IdTrait, ActiveTrait;
//region SECTION: Fields
    private ?string $track = null;
//endregion Fields

//region SECTION: Protected
    /**
     * @param string $active
     */
    protected function setActive(string $active): void
    {
        $this->active = $active;
    }

    /**
     * @param int|null $id
     */
    protected function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $track
     */
    protected function setTrack(string $track): void
    {
        $this->track = $track;
    }
//endregion Protected
//region SECTION: Dto
    /**
     * @param Request $request
     * @return DtoInterface
     */
    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $id     = $request->get(ModelInterface::ID);
            $track   = $request->get(ModelInterface::TRACK);
            $active = $request->get(ModelInterface::ACTIVE);

            if ($active) {
                $this->setActive($active);
            }

            if ($track) {
                $this->setTrack($track);
            }

            if ($id) {
                $this->setId($id);
            }
        }

        return $this;
    }
//endregion SECTION: Dto

//region SECTION: Getters/Setters
    public function getTrack(): string
    {
        return $this->track;
    }

    public function hasTrack(): bool
    {
        return !!$this->track;
    }
//endregion Getters/Setters
}