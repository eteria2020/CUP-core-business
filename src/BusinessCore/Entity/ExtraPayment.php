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
class ExtraPayment extends BusinessPayment
{
    const CLASS_NAME = __CLASS__;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.business_trip_payment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var BusinessTrip
     * @ORM\Column(name="reason", type="string", nullable=true)
     */
    private $reason;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Transaction", inversedBy="businessTripPayments", cascade={"persist"})
     * @ORM\JoinTable(name="business.business_trip_payment_transaction",
     *      joinColumns={@ORM\JoinColumn(name="business_trip_payment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="transaction_id", referencedColumnName="id")}
     *      )
     */
    private $transactions;

    /**
     * BusinessPayment constructor.
     * @param Business $business
     * @param string $reason
     * @param int $amount
     * @param string $currency
     */
    public function __construct(Business $business, $reason, $amount, $currency)
    {
        $this->business = $business;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->createdTs = date_create();
        $this->status = self::STATUS_PENDING;
        $this->transactions = new ArrayCollection();
        $this->reason = $reason;
    }

    public function addTransaction(Transaction $transaction)
    {
        $transaction->addExtraPayment($this);
        $this->transactions->add($transaction);
    }
}
