<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessFare
 *
 * @ORM\Table(name="business_fare", schema="business")
 * @ORM\Entity()
 */
class BusinessFare
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.business_fare_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var Fare
     * @ORM\ManyToOne(targetEntity="Fare")
     * @ORM\JoinColumn(name="base_fare_id", referencedColumnName="id", nullable=false)
     */
    private $baseFare;

    /**
     * @var integer
     * example: 25 -> 25%
     * @ORM\Column(name="park_discount", type="integer", nullable=false)
     */
    private $parkDiscount;

    /**
     * @var integer
     * example: 25 -> 25%
     * @ORM\Column(name="motion_discount", type="integer", nullable=false)
     */
    private $motionDiscount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted_ts", type="datetime", nullable=false)
     */
    private $insertedTs;


    /**
     * BusinessFare constructor.
     * @param Business $business
     * @param Fare $baseFare
     * @param $parkDiscount
     * @param $motionDiscount
     */
    public function __construct(Business $business, Fare $baseFare, $parkDiscount = 0, $motionDiscount = 0)
    {
        $this->business = $business;
        $this->baseFare = $baseFare;
        $this->parkDiscount = $parkDiscount;
        $this->motionDiscount = $motionDiscount;
        $this->insertedTs = date_create();
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return int
     */
    public function getParkDiscount()
    {
        return $this->parkDiscount;
    }

    /**
     * @return int
     */
    public function getMotionDiscount()
    {
        return $this->motionDiscount;
    }

    /**
     * @return Fare
     */
    public function getBaseFare()
    {
        return $this->baseFare;
    }

    /**
     * @return \DateTime
     */
    public function getInsertedTs()
    {
        return $this->insertedTs;
    }
}
