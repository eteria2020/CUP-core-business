<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessTrip
 *
 * @ORM\Table(name="business_trip", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessTripRepository")

 */
class BusinessTrip
{
    /**
     * @var Trip
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Trip")
     * @ORM\JoinColumn(name="trip_id", referencedColumnName="id", nullable=false)
     */
    private $trip;

    /**
     * @var Business
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var Group
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)
     */
    private $group;

    /**
     * @return Trip
     */
    public function getTrip()
    {
        return $this->trip;
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}
