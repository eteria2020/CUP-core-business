<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\BusinessTripPayment;
use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Entity\Repository\BusinessTransactionRepository;
use BusinessCore\Entity\SubscriptionPayment;
use BusinessCore\Entity\TimePackagePayment;
use BusinessCore\Entity\BusinessTransaction;

use BusinessCore\Payment\BusinessPaymentRequest;
use Doctrine\ORM\EntityManager;
use MvlabsPayments\PaymentRequest\PaymentRequest;
use MvlabsPayments\Transaction;

class TransactionService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var BusinessTimePackageService
     */
    private $timePackageService;
    /**
     * @var BusinessService
     */
    private $businessService;
    /**
     * @var BusinessTransactionRepository
     */
    private $businessTransactionRepository;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessTimePackageService $timePackageService
     * @param BusinessService $businessService
     * @param BusinessTransactionRepository $businessTransactionRepository
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessTimePackageService $timePackageService,
        BusinessService $businessService,
        BusinessTransactionRepository $businessTransactionRepository
    ) {
        $this->entityManager = $entityManager;
        $this->timePackageService = $timePackageService;
        $this->businessService = $businessService;
        $this->businessTransactionRepository = $businessTransactionRepository;
    }

    public function assignTransactionToPayments(BusinessPaymentRequest $request, Transaction $transaction)
    {
        $this->entityManager->beginTransaction();
        try {
            $businessTransaction =
                new BusinessTransaction($transaction->amountCents(), $transaction->currency());
            $payments = $request->getPayments();
            foreach ($payments as $payment) {
                $payment->addTransaction($businessTransaction);
                $this->entityManager->persist($payment);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
            $transaction->setId($businessTransaction->getId());
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function transactionCompleted(BusinessTransaction $transaction)
    {
        $transaction->success();
        $this->setPaymentsAsCompleted($transaction);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    public function transactionFailed(BusinessTransaction $transaction)
    {
        $transaction->failed();
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }


    private function timePackagesSuccessfullyPayed($timePackagePayments)
    {
        /** @var TimePackagePayment $timePackagePayment */
        foreach ($timePackagePayments as $timePackagePayment) {
            $timePackagePayment->confirmPayed();
            $this->entityManager->persist($timePackagePayment);
            $this->entityManager->flush();
            $this->timePackageService->enableTimePackage(
                $timePackagePayment->getBusiness(),
                $timePackagePayment->getTimePackage()
            );
        }
    }

    private function extrasSuccessfullyPayed($extraPayments)
    {
        /** @var ExtraPayment $extraPayment */
        foreach ($extraPayments as $extraPayment) {
            $extraPayment->confirmPayed();
            $this->entityManager->persist($extraPayment);
            $this->entityManager->flush();
        }
    }

    private function tripsSuccessfullyPayed($businessTripPayments)
    {
        /** @var BusinessTripPayment $businessTripPayment */
        foreach ($businessTripPayments as $businessTripPayment) {
            $businessTripPayment->confirmPayed();
            $this->entityManager->persist($businessTripPayment);
            $this->entityManager->flush();
        }
    }

    private function subscriptionSuccessfullyPayed($subscriptionPayments)
    {
        /** @var SubscriptionPayment $subscriptionPayment */
        foreach ($subscriptionPayments as $subscriptionPayment) {
            $subscriptionPayment->confirmPayed();
            $this->entityManager->persist($subscriptionPayment);
            $this->entityManager->flush();
            $this->businessService->approveEmployeesWaitingForBusinessEnabling($subscriptionPayment->getBusiness());
        }
    }

    /**
     * @param BusinessTransaction $transaction
     */
    private function setPaymentsAsCompleted(BusinessTransaction $transaction)
    {
        $this->subscriptionSuccessfullyPayed($transaction->getSubscriptionPayments());
        $this->timePackagesSuccessfullyPayed($transaction->getTimePackagePayments());
        $this->extrasSuccessfullyPayed($transaction->getExtraPayments());
        $this->tripsSuccessfullyPayed($transaction->getBusinessTripPayments());
    }

    /**
     * @param $id
     * @return BusinessTransaction
     */
    public function getTransactionFromId($id)
    {
        return $this->businessTransactionRepository->findOneBy(["id" => $id]);
    }

    public function firstTransactionCompleted(BusinessTransaction $transaction, $params)
    {
        $databaseTransactionAmount = $transaction->getAmount();
        $databaseTransactionCurrency = $transaction->getCurrency();
        $paymentAmount = $params['amount'];
        $paymentCurrency = $params['currency'];

        if ($databaseTransactionAmount != $paymentAmount || $databaseTransactionCurrency != $paymentCurrency) {
            throw new \Exception();
        } else {
            $this->transactionCompleted($transaction);
        }
    }
}
