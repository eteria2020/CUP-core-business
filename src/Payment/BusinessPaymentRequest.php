<?php

namespace BusinessCore\Payment;

use BusinessCore\Entity\Base\BusinessPayment;
use MvlabsPayments\Customer;
use MvlabsPayments\PaymentRequest\PaymentRequest;
use MvlabsPayments\Values\Amount;

class BusinessPaymentRequest implements PaymentRequest
{
    private $customer;

    private $payments;

    private $amount;

    private $firstPayment;

    /**
     * BusinessPaymentRequest constructor.
     * @param Customer $customer
     * @param array $payments
     * @param bool $firstPayment
     */
    public function __construct(Customer $customer, array $payments, $firstPayment = false)
    {
        $this->customer = $customer;
        $this->payments = $payments;
        $this->setAmountFromPayments();
        $this->firstPayment = $firstPayment;
    }

    /**
     * @return Customer
     */
    public function customer()
    {
        return $this->customer;
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

    private function setAmountFromPayments()
    {
        $tot = 0;
        $currency = '';
        /** @var BusinessPayment $payment */
        foreach ($this->payments as $payment) {
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
