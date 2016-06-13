<?php

namespace BusinessCore\Entity;

use BusinessCore\Entity\Base\BusinessPayment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Payments\Customer;
use Payments\PaymentRequest\PaymentRequest;
use Payments\Value\Amount;

/**
 * TimePackagePayment
 *
 * @ORM\Table(name="time_package_payment", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessPaymentRepository")
 */
class TimePackagePayment extends BusinessPayment
{
    const CLASS_NAME = __CLASS__;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.time_package_payment_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var TimePackage
     * @ORM\ManyToOne(targetEntity="TimePackage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="time_package_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $timePackage;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Transaction", inversedBy="timePackagePayments", cascade={"persist"})
     * @ORM\JoinTable(name="business.time_package_payment_transaction",
     *      joinColumns={@ORM\JoinColumn(name="time_package_payment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="transaction_id", referencedColumnName="id")}
     *      )
     */
    private $transactions;

    /**
     * BusinessPayment constructor.
     * @param Business $business
     * @param TimePackage $timePackage
     * @param int $amount
     * @param string $currency
     */
    public function __construct(Business $business, TimePackage $timePackage, $amount, $currency)
    {
        $this->business = $business;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->createdTs = date_create();
        $this->status = self::STATUS_PENDING;
        $this->transactions = new ArrayCollection();
        $this->timePackage = $timePackage;
    }

    /**
     * @return TimePackage
     */
    public function getTimePackage()
    {
        return $this->timePackage;
    }

    public function addTransaction(Transaction $transaction)
    {
        $transaction->addTimePackagePayment($this);
        $this->transactions->add($transaction);
    }
}
