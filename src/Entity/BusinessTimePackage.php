<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessTimePackage
 *
 * Association between a business and a PURCHASED time package
 *
 * @ORM\Table(name="business_time_package", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessTimePackageRepository")
 */
class BusinessTimePackage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.business_time_package_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="businessTimePackages")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var TimePackage
     * @ORM\ManyToOne(targetEntity="TimePackage")
     * @ORM\JoinColumn(name="time_package_id", referencedColumnName="id", nullable=false)
     */
    private $timePackage;

    /**
     * @var int
     *
     * @ORM\Column(name="residual_minutes", type="integer", nullable=false)
     */
    private $residualMinutes;

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

    /**
     * BusinessTimePackage constructor.
     * @param Business $business
     * @param TimePackage $timePackage
     */
    public function __construct(Business $business, TimePackage $timePackage)
    {
        $this->business = $business;
        $this->timePackage = $timePackage;
        $this->residualMinutes = $timePackage->getMinutes();
        $this->insertedTs = date_create();
        $this->updatedTs = date_create();
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

    /**
     * @return int
     */
    public function getResidualMinutes()
    {
        return $this->residualMinutes;
    }

    /**
     * @param int $residualMinutes
     */
    public function setResidualMinutes($residualMinutes)
    {
        $this->residualMinutes = $residualMinutes;
        $this->updatedTs = date_create();
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedTs()
    {
        return $this->updatedTs;
    }
}
