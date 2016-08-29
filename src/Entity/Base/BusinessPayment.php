<?php

namespace BusinessCore\Entity\Base;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessInvoice;
use BusinessCore\Entity\BusinessTransaction;
use Doctrine\ORM\Mapping as ORM;

abstract class BusinessPayment
{
    const STATUS_PENDING = 'pending';
    const STATUS_EXPECTED_PAYED = 'expected_payed';
    const STATUS_CONFIRMED_PAYED = 'confirmed_payed';
    const STATUS_INVOICED = 'invoiced';
    const STATUS_CANCELLED = 'cancelled';

    const TYPE_PACKAGE = 'package';
    const TYPE_TRIP = 'trip';
    const TYPE_EXTRA = 'extra';
    const TYPE_SUBSCRIPTION = 'subscription';

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     * })
     */
    protected $business;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="text", nullable=false)
     */
    protected $currency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_ts", type="datetime", nullable=false)
     */
    protected $createdTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payed_on_ts", type="datetime", nullable=true)
     */
    protected $payedOnTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expected_payed_ts", type="datetime", nullable=true)
     */
    protected $expectedPayedTs;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="text", nullable=false)
     */
    protected $status;

    /**
     * @var BusinessInvoice
     * @ORM\ManyToOne(targetEntity="BusinessInvoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=true)
     * })
     */
    protected $businessInvoice;

    /**
     * BusinessPayment constructor.
     * @param Business $business
     * @param int $amount
     * @param string $currency
     */
    public function __construct(Business $business, $amount, $currency)
    {
        $this->business = $business;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->createdTs = date_create();
        $this->status = self::STATUS_PENDING;
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
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedTs()
    {
        return $this->createdTs;
    }


    /**
     * @return \DateTime
     */
    public function getPayedOnTs()
    {
        return $this->payedOnTs;
    }

    /**
     * @return \DateTime
     */
    public function getExpectedPayedTs()
    {
        return $this->expectedPayedTs;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return BusinessInvoice
     */
    public function getBusinessInvoice()
    {
        return $this->businessInvoice;
    }

    public function cancel()
    {
        $this->status = self::STATUS_CANCELLED;
    }

    public function confirmPayed()
    {
        $this->status = self::STATUS_CONFIRMED_PAYED;
        $this->payedOnTs = date_create();
    }

    public function flagAsExpectedPayed()
    {
        $this->status = self::STATUS_EXPECTED_PAYED;
        $this->expectedPayedTs = date_create();
    }

    public function isPayed()
    {
        return $this->status === self::STATUS_CONFIRMED_PAYED;
    }

    public function isExpectedPayed()
    {
        return $this->status === self::STATUS_EXPECTED_PAYED;
    }

    /**
     * @param BusinessInvoice $businessInvoice
     */
    public function setBusinessInvoice(BusinessInvoice $businessInvoice)
    {
        $this->businessInvoice = $businessInvoice;
        $this->status = self::STATUS_INVOICED;
    }

    abstract public function addTransaction(BusinessTransaction $transaction);
}
