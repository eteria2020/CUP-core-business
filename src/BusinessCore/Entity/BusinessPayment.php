<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="business_payment", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessPaymentRepository")

 */
class BusinessPayment
{
    const TIME_PACKAGE_TYPE = 'time_package';

    const STATUS_PENDING = 'pending';
    const STATUS_EXPECTED_PAYED = 'expected_payed';
    const STATUS_CONFIRMED_PAYED = 'confirmed_payed';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.business_payment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     * })
     */
    private $business;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="text", nullable=false)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_ts", type="datetime", nullable=false)
     */
    private $createdTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payed_on_ts", type="datetime", nullable=true)
     */
    private $payedOnTs;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="text", nullable=false)
     */
    private $status;

    /**
     * @var BusinessInvoice
     * @ORM\ManyToOne(targetEntity="BusinessInvoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $businessInvoice;

    /**
     * @ORM\ManyToMany(targetEntity="Transaction", inversedBy="payments")
     * @ORM\JoinTable(name="business.payment_transaction")
     */
    private $transactions;

    /**
     * BusinessPayment constructor.
     * @param Business $business
     * @param int $amount
     * @param string $currency
     * @param string $type
     */
    public function __construct(Business $business, $amount, $currency, $type)
    {
        $this->business = $business;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->type = $type;
        $this->createdTs = date_create();
        $this->status = self::STATUS_PENDING;
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
}
