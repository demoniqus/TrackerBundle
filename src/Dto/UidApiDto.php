<?php


namespace Evrinoma\UidBundle\Dto;


use Evrinoma\DtoBundle\Dto\AbstractDto;
use Evrinoma\DtoBundle\Dto\DtoInterface;
use Evrinoma\DtoCommon\ValueObject\Immutable\ActiveTrait;
use Evrinoma\DtoCommon\ValueObject\Immutable\IdTrait;
use Evrinoma\UidBundle\Model\ModelInterface;
use Symfony\Component\HttpFoundation\Request;

class UidApiDto extends AbstractDto implements UidApiDtoInterface
{
    use IdTrait, ActiveTrait;
//region SECTION: Fields
    private ?string $uid = null;
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
     * @param string $uid
     */
    protected function setUid(string $uid): void
    {
        $this->uid = $uid;
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
            $uid   = $request->get(ModelInterface::UID);
            $active = $request->get(ModelInterface::ACTIVE);

            if ($active) {
                $this->setActive($active);
            }

            if ($uid) {
                $this->setUid($uid);
            }

            if ($id) {
                $this->setId($id);
            }
        }

        return $this;
    }
//endregion SECTION: Dto

//region SECTION: Getters/Setters
    public function getUid(): string
    {
        return $this->uid;
    }

    public function hasUid(): bool
    {
        return !!$this->uid;
    }
//endregion Getters/Setters
}