<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessRate
 *
 * @ORM\Table(name="business_rate", schema="business")
 * @ORM\Entity()
 */
class BusinessRate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.business_rate_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Business
     * @ORM\OneToOne(targetEntity="Business", inversedBy="business_rate")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var decimal
     * @ORM\Column(name="discount_stop", type="decimal", precision=5, scale=4, nullable=false)
     */
    private $discountStop;

    /**
     * @var decimal
     * @ORM\Column(name="discount_trip", type="decimal", precision=5, scale=4, nullable=false)
     */
    private $discountTrip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted_ts", type="datetime", nullable=false)
     */
    private $insertedTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_ts", type="datetime", nullable=false)
     */
    private $updatedTs;

    public function __construct(
        Business $business,
        $discountStop,
        $discountTrip
    ) {
        $this->business = $business;
        $this->discountStop = $discountStop;
        $this->discountTrip = $discountTrip;
        $this->insertedTs = date_create();
        $this->updatedTs = date_create();
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return decimal
     */
    public function getDiscountStop()
    {
        return $this->discountStop;
    }

    /**
     * @return decimal
     */
    public function getDiscountTrip()
    {
        return $this->discountTrip;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedTs()
    {
        return $this->updatedTs;
    }

    /**
     * @param decimal $discountStop
     */
    public function setDiscountStop($discountStop)
    {
        $this->discountStop = $discountStop;
        $this->updatedTs = date_create();
    }

    /**
     * @param decimal $discountTrip
     */
    public function setDiscountTrip($discountTrip)
    {
        $this->discountTrip = $discountTrip;
        $this->updatedTs = date_create();
    }
}
