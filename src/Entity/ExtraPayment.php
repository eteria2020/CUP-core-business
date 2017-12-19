<?php

namespace BusinessCore\Entity;

use BusinessCore\Entity\Base\BusinessPayment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExtraPayment
 *
 * @ORM\Table(name="extra_payment", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessPaymentRepository")
 */
class ExtraPayment extends BusinessPayment {

    const CLASS_NAME = __CLASS__;
    const EXTRA_PAYMENT_TYPE_EXTRA = 'extra';
    const EXTRA_PAYMENT_PENALITY = 'penality';
    const EXTRA_PAYMENT_CREDIT_CARD_CHANGE = 'credit_card_change';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.extra_payment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", nullable=true)
     */
    private $reason;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="BusinessTransaction", inversedBy="extraPayments", cascade={"persist"})
     * @ORM\JoinTable(name="business.extra_payment_transaction",
     *      joinColumns={@ORM\JoinColumn(name="extra_payment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="transaction_id", referencedColumnName="id")}
     *      )
     */
    private $transactions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="invoice_at", type="datetime", nullable=true, options={"default": 0})
     */
    private $invoiceAt;

    /**
     * @var BusinessFleet
     *
     * @ORM\ManyToOne(targetEntity="BusinessFleet")
     * @ORM\JoinColumn(name="fleet_id", referencedColumnName="id", nullable=false)
     */
    private $fleet;

    /**
     * @var boolean
     *
     * @ORM\Column(name="invoce_able", type="boolean", nullable=false, options={"default": true})
     */
    private $invoiceAble;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string", nullable=false)
     */
    private $paymentType;

    /**
     * BusinessPayment constructor
     *
     * @param Business $business
     * @param string $reason
     * @param int $amount
     * @param string $currency
     */
    public function __construct(Business $business, $reason, $amount, $currency) {
        $this->reason = $reason;
        $this->transactions = new ArrayCollection();
        $this->invoiceAble = true;
        $this->paymentType = self::EXTRA_PAYMENT_TYPE_EXTRA;
        $this->fleet = $business->getFleet();
        parent::__construct($business, $amount, $currency);
    }

    public function addTransaction(BusinessTransaction $transaction) {
        $transaction->addExtraPayment($this);
        $this->transactions->add($transaction);
    }

    /**
     * @return string
     */
    public function getReason() {
        return $this->reason;
    }

    /**
     * @return datetime
     */
    public function getInvoiceAt() {
        return $this->invoiceAt;
    }

    /**
     * @param datetine $invoiceAt
     */
    public function setInvoiceAt($invoiceAt) {
        $this->invoiceAt = $invoiceAt;
    }

    /**
     * @return BusinessFleet
     */
    public function getFeeel() {
        return $this->fleet;
    }

    /**
     * @param BusinessFleet $fleet
     */
    public function setFleet($fleet) {
        $this->fleet = $fleet;
    }

    /**
     * @return boolean
     */
    public function getInvoiceAble() {
        return $this->invoiceAble;
    }

    /**
     * @param boolean $invoiceAble
     */
    public function setInvoiceAble($invoiceAble) {
        $this->invoiceAble = $invoiceAble;
    }

    /**
     * @return string
     */
    public function getPaymentType() {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     */
    public function setPaymentType($paymentTypeString) {
        switch ($paymentTypeString) {
            case self::EXTRA_PAYMENT_PENALITY;
                $paymentType = self::EXTRA_PAYMENT_PENALITY;
                break;
            case self::EXTRA_PAYMENT_CREDIT_CARD_CHANGE;
                $paymentType = self::EXTRA_PAYMENT_CREDIT_CARD_CHANGE;
                break;
            default:
                $paymentType = self::EXTRA_PAYMENT_TYPE_EXTRA;
        }

        $this->paymentType = $paymentType;
    }

}
