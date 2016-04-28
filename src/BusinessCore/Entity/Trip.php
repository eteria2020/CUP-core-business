<?php

namespace BusinessCore\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trip
 *
 * @ORM\Table(name="trip", schema="business")
 * @ORM\Entity(readOnly=true)
 */

class Trip
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var Employee
     *
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * })
     */
    private $employee;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="timestamp_beginning", type="datetimetz", nullable=false)
     */
    private $timestampBeginning;

    /**
     * @var integer
     *
     * @ORM\Column(name="km_beginning", type="integer", nullable=false)
     */
    private $kmBeginning;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude_beginning", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $longitudeBeginning;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude_beginning", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $latitudeBeginning;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_beginning", type="string", nullable=false)
     */
    private $geoBeginning;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="beginning_tx", type="datetimetz", nullable=false)
     */
    private $beginningTx;

    /**
     * @var string
     *
     * @ORM\Column(name="address_beginning", type="text", nullable=true)
     */
    private $addressBeginning;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp_end", type="datetimetz", nullable=false)
     */
    private $timestampEnd;

    /**
     * @var integer
     *
     * @ORM\Column(name="km_end", type="integer", nullable=false)
     */
    private $kmEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude_end", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $longitudeEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude_end", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $latitudeEnd;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_end", type="string", nullable=false)
     */
    private $geoEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_tx", type="datetimetz", nullable=false)
     */
    private $endTx;

    /**
     * @var string
     *
     * @ORM\Column(name="address_end", type="text", nullable=true)
     */
    private $addressEnd;

    /**
     * @var integer
     *
     * @ORM\Column(name="park_seconds", type="integer", nullable=false)
     */
    private $parkSeconds;

    /**
     * @var string
     *
     * @ORM\Column(name="car_plate", type="string", nullable=false)
     */
    private $carPlate;

    /**
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @return DateTime
     */
    public function getTimestampBeginning()
    {
        return $this->timestampBeginning;
    }

    /**
     * @return int
     */
    public function getKmBeginning()
    {
        return $this->kmBeginning;
    }

    /**
     * @return DateTime
     */
    public function getTimestampEnd()
    {
        return $this->timestampEnd;
    }

    /**
     * @return int
     */
    public function getKmEnd()
    {
        return $this->kmEnd;
    }

    /**
     * @return string
     */
    public function getCarPlate()
    {
        return $this->carPlate;
    }

    /**
     * @return int
     */
    public function getParkSeconds()
    {
        return $this->parkSeconds;
    }

    /**
     * @return string
     */
    public function getAddressEnd()
    {
        return $this->addressEnd;
    }

    /**
     * @return string
     */
    public function getAddressBeginning()
    {
        return $this->addressBeginning;
    }



}
