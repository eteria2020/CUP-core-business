<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Transaction;
use Payments\PaymentRequest\PaymentRequest;
use Zend\EventManager\EventManager;

class MockExternalPaymentService
{

    private $eventManager;

    public function __construct()
    {
        $this->eventManager = new EventManager('Transaction');
    }

    public function pay(PaymentRequest $request)
    {
        $transaction = new Transaction($request->amount()->cents(), $request->amount()->currency());
        $this->eventManager->trigger('paymentInitiated', $this, [
            'transaction' => $transaction,
            'requestPpayment' => $request
        ]);

        //try to pay transaction (possibly async)

        $this->eventManager->trigger('paymentOutcome', $this, [
            'transaction' => $transaction,
            'outcome' => "OK"
        ]);
    }
}
