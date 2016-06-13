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

    /**
     * BusinessPaymentRequest constructor.
     * @param $customer
     * @param $payments
     */
    public function __construct($customer, array $payments)
    {
        $this->customer = $customer;
        $this->payments = $payments;
        $this->amount = $this->getAmountFromPayments($payments);
    }

    /**
     * @return Customer
     */
    public function customer()
    {
        // TODO: Implement customer() method.
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
        // TODO: Implement isFirstPayment() method.
    }

    private function getAmountFromPayments(array $payments)
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
