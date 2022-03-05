<?php


namespace Demoniqus\UidBundle\Entity\Uid;


use Demoniqus\UidBundle\Model\Uid\AbstractUid;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="e_uid")
 * @ORM\Entity()
 */
final class BaseUid extends AbstractUid
{

}