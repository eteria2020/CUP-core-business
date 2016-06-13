<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Base\BusinessPayment;
use BusinessCore\Entity\BusinessTripPayment;
use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Entity\TimePackagePayment;
use BusinessCore\Entity\Transaction;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

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
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessTimePackageService $timePackageService
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessTimePackageService $timePackageService
    ) {
        $this->entityManager = $entityManager;
        $this->timePackageService = $timePackageService;
    }

    public function assignTransactionToPayments(array $payments, Transaction $transaction)
    {
        $this->entityManager->beginTransaction();
        try {
            /** @var BusinessPayment $payment */
            foreach ($payments as $payment) {
                $payment->addTransaction($transaction);
                $this->entityManager->persist($payment);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function transactionCompleted($outcome, Transaction $transaction)
    {
        if ($outcome == "OK") {
            $transaction->success();
            $this->successfullTransaction($transaction);
        } else {
            $transaction->failed();
        }
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    private function successfullTransaction(Transaction $transaction)
    {
        $this->timePackagesSuccessfullyPayed($transaction->getTimePackagePayments());
        $this->extrasSuccessfullyPayed($transaction->getExtraPayments());
        $this->tripsSuccessfullyPayed($transaction->getBusinessTripPayments());
    }

    private function timePackagesSuccessfullyPayed($timePackagePayments)
    {
        /** @var TimePackagePayment $timePackagePayment */
        foreach ($timePackagePayments as $timePackagePayment) {
            $timePackagePayment->confirmPayed();
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
        }
    }

    private function tripsSuccessfullyPayed($businessTripPayments)
    {
        /** @var BusinessTripPayment $businessTripPayment */
        foreach ($businessTripPayments as $businessTripPayment) {
            $businessTripPayment->confirmPayed();
        }
    }
}
