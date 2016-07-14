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

    public function __construct(Business $business, $amount, $currency)
    {
        $this->transactions = new ArrayCollection();
        parent::__construct($business, $amount, $currency);
    }


    public function addTransaction(Transaction $transaction)
    {
        $transaction->addSubscriptionPayment($this);
        $this->transactions->add($transaction);
    }

    public function confirmPayed()
    {
        $this->business->enable();
        parent::confirmPayed();
    }
}
