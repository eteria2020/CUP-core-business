<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use MvlabsPayments\Contract\Contract;

/**
 * BusinessContract
 *
 * @ORM\Table(name="contract", schema="business")
 * @ORM\Entity()
 */
class BusinessContract extends Contract
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.contract_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var string
     *
     * @ORM\Column(name="pan", type="string", length=19, nullable=true)
     */
    private $pan;

    /**
     * @var string format aaaamm
     *
     * @ORM\Column(name="pan_expiry", type="string", length=6, nullable=true)
     */
    private $panExpiry;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_ts", type="datetime", nullable=true)
     */
    private $createdTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="disabled_date", type="datetime", nullable=true)
     */
    private $disabledDate;


    /**
     * BusinessContract constructor.
     * @param $business
     */
    public function __construct($business)
    {
        $this->business = $business;
        $this->createdTs = date_create();
        parent::__construct();
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return string
     */
    public function getPan()
    {
        return $this->pan;
    }

    /**
     * @param string $pan
     */
    public function setPan($pan)
    {
        $this->pan = $pan;
    }

    /**
     * @return string
     */
    public function getPanExpiry()
    {
        return $this->panExpiry;
    }

    /**
     * @param string $panExpiry
     */
    public function setPanExpiry($panExpiry)
    {
        $this->panExpiry = $panExpiry;
    }

    public function disable()
    {
        $this->disabledDate = date_create();
    }

}
