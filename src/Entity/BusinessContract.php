<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use MvlabsPayments\Contract\Contract;

/**
 * BusinessContract
 *
 * @ORM\Table(name="contract", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessContractRepository")
 */
class BusinessContract
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.contract_id_seq", allocationSize=1, initialValue=10000)
     */
    private $id;

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="businessContracts")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

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
        $this->disabledDate = date_create();    //be careful, contract is disable
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

    public function enable()
    {
        $this->disabledDate = null;
    }

    public function isDisabled()
    {
        return !is_null($this->disabledDate);
    }

    public function getPaymentContract()
    {
        return new Contract($this->id, $this->panExpiry);
    }
    public function isActive()
    {
        return !is_null($this->panExpiry) && is_null($this->disabledDate);
    }

    public function getCreatedDate()
    {
        return $this->createdTs->format('Y-m-d H:i:s');
    }
}
