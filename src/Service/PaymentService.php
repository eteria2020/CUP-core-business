<?php

namespace BusinessCore\Service;

//use BusinessCore\Entity\BusinessContract;
//use BusinessCore\Entity\BusinessTransaction;
use MvlabsPayments\PaymentRequest\PaymentRequest;
use MvlabsPayments\Payments\Payment;
//use Zend\EventManager\EventManager;

class PaymentService
{
    /**
     * @var Payment
     */
    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function pay(PaymentRequest $request)
    {
        $this->payment->pay($request);
    }

    public function completePayment()
    {
        $this->payment->completePayment();
    }
}
