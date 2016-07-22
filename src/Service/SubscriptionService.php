<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\SubscriptionPayment;
use BusinessCore\Payment\BusinessPaymentRequest;
use Doctrine\ORM\EntityManager;

class SubscriptionService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PaymentService
     */
    private $paymentService;
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param PaymentService $paymentService
     * @param TransactionService $transactionService
     */
    public function __construct(
        EntityManager $entityManager,
        PaymentService $paymentService,
        TransactionService $transactionService
    ) {
        $this->entityManager = $entityManager;
        $this->paymentService = $paymentService;
        $this->transactionService = $transactionService;
    }

    public function paySubscription(SubscriptionPayment $subscriptionPayment)
    {
        $business = $subscriptionPayment->getBusiness();

        $customer = $business->getPaymentCustomer();
        $businessPayment = new BusinessPaymentRequest($customer, [$subscriptionPayment], true);

        $this->paymentService->pay($businessPayment);
    }

    public function concludedSubscriptionPayment($params)
    {
        if ($params['esito'] == 'KO') {
            $transaction = $this->transactionService->getTransactionFromId($params['codTrans']);
            $transaction->failed();
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();
            return false;
        } else {
            $this->paymentService->completePayment();
            return true;
        }
    }

    public function rejectedSubscriptionPayment($codTrans)
    {
        $transaction = $this->transactionService->getTransactionFromId($codTrans);
        $transaction->failed();
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
