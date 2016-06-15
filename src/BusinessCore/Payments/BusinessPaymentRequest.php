<?php
namespace BusinessCore\Payments;

use BusinessCore\Entity\Base\BusinessPayment;
use Payments\Customer;
use Payments\PaymentRequest\PaymentRequest;
use Payments\Value\Amount;

class BusinessPaymentRequest implements PaymentRequest
{
    private $customer;

    private $payments;

    private $amount;

    private $firstPayment;

    /**
     * BusinessPaymentRequest constructor.
     * @param $customer
     * @param array $payments
     * @param bool $firstPayment
     */
    public function __construct($customer, array $payments, $firstPayment = false)
    {
        $this->customer = $customer;
        $this->payments = $payments;
        $this->setAmountFromPayments($payments);
        $this->firstPayment = $firstPayment;
    }

    /**
     * @return Customer
     */
    public function customer()
    {
        return $this->business;
    }

    /**
     * @return Amount
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return bool
     */
    public function isFirstPayment()
    {
        return $this->firstPayment;
    }

    private function setAmountFromPayments(array $payments)
    {
        $tot = 0;
        $currency = '';
        /** @var BusinessPayment $payment */
        foreach ($payments as $payment) {
            $tot += $payment->getAmount();
            $currency = $payment->getCurrency();
        }

        $this->amount = new Amount($tot, $currency);
    }

    /**
     * @return array
     */
    public function getPayments()
    {
        return $this->payments;
    }
}
