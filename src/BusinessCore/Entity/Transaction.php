<?php

namespace BusinessCore\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction", schema="business")
 * @ORM\Entity()

 */
class Transaction
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.transaction_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @ORM\Column(name="outcome", type="text", nullable=true)
     */
    private $outcome;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_ts", type="datetime", nullable=false)
     */
    private $createdTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="outcome_ts", type="datetime", nullable=true)
     */
    private $outcomeTs;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="TimePackagePayment", mappedBy="transactions")
     */
    private $timePackagePayments;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="BusinessTripPayment", mappedBy="transactions")
     */
    private $businessTripPayments;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="ExtraPayment", mappedBy="transactions")
     */
    private $extraPayments;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="SubscriptionPayment", mappedBy="transactions")
     */
    private $subscriptionPayments;

    /**
     * Transaction constructor.
     * @param int $amount
     * @param string $currency
     */
    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->createdTs = date_create();
        $this->timePackagePayments = new ArrayCollection();
    }

    public function success()
    {
        $this->outcome = "OK";
        $this->outcomeTs = date_create();
    }

    public function failed()
    {
        $this->outcome = "KO";
        $this->outcomeTs = date_create();
    }

    /**
     * @return ArrayCollection
     */
    public function getTimePackagePayments()
    {
        return $this->timePackagePayments;
    }

    public function addTimePackagePayment(TimePackagePayment $timePackagePayment)
    {
        $this->timePackagePayments->add($timePackagePayment);
    }

    /**
     * @return ArrayCollection
     */
    public function getBusinessTripPayments()
    {
        return $this->businessTripPayments;
    }

    public function addBusinessTripPayment(BusinessTripPayment $businessTripPayment)
    {
        $this->businessTripPayments->add($businessTripPayment);
    }

    /**
     * @return ArrayCollection
     */
    public function getExtraPayments()
    {
        return $this->businessTripPayments;
    }

    public function addExtraPayment(ExtraPayment $extraPayment)
    {
        $this->extraPayments->add($extraPayment);
    }

    /**
     * @return ArrayCollection
     */
    public function getSubscriptionPayments()
    {
        return $this->subscriptionPayments;
    }

    public function addSubscriptionPayment(SubscriptionPayment $subscriptionPayment)
    {
        $this->subscriptionPayments->add($subscriptionPayment);
    }
}
