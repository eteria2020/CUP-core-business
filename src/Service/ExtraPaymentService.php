<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Payment\BusinessPaymentRequest;
use Doctrine\ORM\EntityManager;

class ExtraPaymentService {

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
    EntityManager $entityManager, PaymentService $paymentService, TransactionService $transactionService
    ) {
        $this->entityManager = $entityManager;
        $this->paymentService = $paymentService;
        $this->transactionService = $transactionService;
    }

    public function payCreditCardChange(ExtraPayment $extraPayment) {
        $business = $extraPayment->getBusiness();

        $customer = $business->getPaymentCustomer();
        $businessPayment = new BusinessPaymentRequest($customer, [$extraPayment], true);
        $this->paymentService->pay($businessPayment);
    }

//    public function concludedCreditCardChange($params) {
//        if ($params['esito'] == 'KO') {
//            $transaction = $this->transactionService->getTransactionFromId($params['codTrans']);
//            $transaction->failed();
//            $this->entityManager->persist($transaction);
//            $this->entityManager->flush();
//            return false;
//        } else {
//            $this->paymentService->completePayment();
//            return true;
//        }
//    }
//
//    public function rejectedCreditCardChange($codTrans) {
//        $transaction = $this->transactionService->getTransactionFromId($codTrans);
//        $transaction->failed();
//        $this->entityManager->persist($transaction);
//        $this->entityManager->flush();
//    }

}
