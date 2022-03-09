<?php


namespace Demoniqus\TrackerBundle\Entity\Tracker;


use Demoniqus\TrackerBundle\Model\Tracker\AbstractTracker;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="e_tracker")
 * @ORM\Entity()
 */
final class BaseTracker extends AbstractTracker
{

}