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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.business_trip_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;
    /**
     * @var Trip
     * @ORM\ManyToOne(targetEntity="Trip")
     * @ORM\JoinColumn(name="trip_id", referencedColumnName="id", nullable=false)
     */
    private $trip;

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var Group
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
