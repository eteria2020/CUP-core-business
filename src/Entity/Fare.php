<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fare
 *
 * @ORM\Table(name="fare", schema="business")
 * @ORM\Entity(readOnly=true, repositoryClass="BusinessCore\Entity\Repository\FareRepository")
 */
class Fare
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var interger cost in eurocents
     *
     * @ORM\Column(name="motion_cost_per_minute", type="integer", nullable=false)
     */
    private $motionCostPerMinute;

    /**
     * @var integer cost in eurocents
     *
     * @ORM\Column(name="park_cost_per_minute", type="integer", nullable=false)
     */
    private $parkCostPerMinute;

    /**
     * @var string json representation of the price steps.
     *
     * every key in the json file represents a minutes quantity and its value
     * is the cost of a trip of those minutes in eurocents.
     *
     * @ORM\Column(name="cost_steps", type="string", nullable=false)
     */
    private $costSteps;

    /**
     * @return interger
     */
    public function getMotionCostPerMinute()
    {
        return $this->motionCostPerMinute;
    }

    /**
     * @return int
     */
    public function getParkCostPerMinute()
    {
        return $this->parkCostPerMinute;
    }

    /**
     * @return string
     */
    public function getCostSteps()
    {
        return $this->costSteps;
    }
}
