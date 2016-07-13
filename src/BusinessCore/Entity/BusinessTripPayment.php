<?php

namespace BusinessCore\Entity;

use BusinessCore\Entity\Base\BusinessPayment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessTripPayment
 *
 * @ORM\Table(name="business_trip_payment", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessPaymentRepository")
 */
class BusinessTripPayment extends BusinessPayment
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
     * @ORM\ManyToOne(targetEntity="BusinessTrip")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="business_trip_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $businessTrip;

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
     * @param BusinessTrip $businessTrip
     * @param int $amount
     * @param string $currency
     */
    public function __construct(Business $business, BusinessTrip $businessTrip, $amount, $currency)
    {
        $this->businessTrip = $businessTrip;
        $this->transactions = new ArrayCollection();
        parent::__construct($business, $amount, $currency);
    }


    public function addTransaction(Transaction $transaction)
    {
        $transaction->addBusinessTripPayment($this);
        $this->transactions->add($transaction);
    }
}
