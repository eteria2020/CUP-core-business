<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\BusinessContract;
use BusinessCore\Entity\Transaction;
use MvlabsPayments\PaymentRequest\PaymentRequest;
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

        if ($request->isFirstPayment()) {
            $contract = new BusinessContract($request->customer());
            $this->eventManager->trigger('contractInitiated', $this, [
                'contract' => $contract
            ]);

            //do things
            $contract->setPan("66666666");
            $contract->setPanExpiry("202007");

            $this->eventManager->trigger('contractFinalized', $this, [
                'contract' => $contract
            ]);
        }
        $this->eventManager->trigger('paymentInitiated', $this, [
            'transaction' => $transaction,
            'requestPayment' => $request
        ]);

        //try to pay transaction (possibly async)

        $this->eventManager->trigger('paymentOutcome', $this, [
            'transaction' => $transaction,
            'outcome' => "OK"
        ]);
    }
}
