<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessTimePackage
 *
 * @ORM\Table(name="business_time_package", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessTimePackageRepository")
 */
class BusinessTimePackage
{
    /**
     * @var Business
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var TimePackage
     * @ORM\Id
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return TimePackage
     */
    public function getTimePackage()
    {
        return $this->timePackage;
    }

    /**
     * @return \DateTime
     */
    public function getInsertedTs()
    {
        return $this->insertedTs;
    }
}
