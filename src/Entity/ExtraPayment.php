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
     * @ORM\SequenceGenerator(sequenceName="business.extra_payment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="reason", type="string", nullable=true)
     */
    private $reason;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="BusinessTransaction", inversedBy="extraPayments", cascade={"persist"})
     * @ORM\JoinTable(name="business.extra_payment_transaction",
     *      joinColumns={@ORM\JoinColumn(name="extra_payment_id", referencedColumnName="id")},
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
        $this->reason = $reason;
        $this->transactions = new ArrayCollection();
        parent::__construct($business, $amount, $currency);
    }

    public function addTransaction(BusinessTransaction $transaction)
    {
        $transaction->addExtraPayment($this);
        $this->transactions->add($transaction);
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
}
