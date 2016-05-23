<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessBuyableTimePackage
 *
 * Association between a business and a PURCHASABLE package
 *
 * @ORM\Table(name="business_buyable_time_package", schema="business")
 * @ORM\Entity()
 */
class BusinessBuyableTimePackage
{
    /**
     * @var Business
     * @ORM\ID
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var TimePackage
     * @ORM\ID
     * @ORM\ManyToOne(targetEntity="TimePackage")
     * @ORM\JoinColumn(name="time_package_id", referencedColumnName="id", nullable=false)
     */
    private $timePackage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted_ts", type="datetime", nullable=false)
     */
    private $insertedTs;

    /**
     * BusinessTimePackage constructor.
     * @param Business $business
     * @param TimePackage $timePackage
     */
    public function __construct(Business $business, TimePackage $timePackage)
    {
        $this->business = $business;
        $this->timePackage = $timePackage;
        $this->insertedTs = date_create();
    }

    /**
     * @return TimePackage
     */
    public function getTimePackage()
    {
        return $this->timePackage;
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }
}
