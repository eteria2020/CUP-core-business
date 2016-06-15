<?php

namespace BusinessCore\Entity;

use BusinessCore\Entity\Base\BusinessPayment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SubscriptionPayment
 *
 * @ORM\Table(name="subscription_payment", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessPaymentRepository")
 */
class SubscriptionPayment extends BusinessPayment
{
    const CLASS_NAME = __CLASS__;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.subscription_payment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Transaction", inversedBy="subscriptionPayments", cascade={"persist"})
     * @ORM\JoinTable(name="business.subscription_payment_transaction",
     *      joinColumns={@ORM\JoinColumn(name="subscription_payment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="transaction_id", referencedColumnName="id")}
     *      )
     */
    private $transactions;

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
        $this->transactions = new ArrayCollection();
    }

    public function addTransaction(Transaction $transaction)
    {
        $transaction->addSubscriptionPayment($this);
        $this->transactions->add($transaction);
    }

    public function confirmPayed()
    {
        $this->business->enableAfterFirstPayment();
        parent::confirmPayed();
    }


}
