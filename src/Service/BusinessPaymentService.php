<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Entity\Repository\BusinessPaymentRepository;
use BusinessCore\Entity\SubscriptionPayment;
use BusinessCore\Entity\TimePackagePayment;
use BusinessCore\Exception\InvalidFormDataException;

use BusinessCore\Service\Helper\SearchCriteria;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class BusinessPaymentService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var BusinessPaymentRepository
     */
    private $businessPaymentRepository;
    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var BusinessTimePackageService
     */
    private $businessTimePackageService;
    /**
     * @var BusinessService
     */
    private $businessService;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessPaymentRepository $businessPaymentRepository
     * @param BusinessTimePackageService $businessTimePackageService
     * @param BusinessService $businessService
     * @param Translator $translator
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessPaymentRepository $businessPaymentRepository,
        BusinessTimePackageService $businessTimePackageService,
        BusinessService $businessService,
        Translator $translator
    ) {
        $this->entityManager = $entityManager;
        $this->businessPaymentRepository = $businessPaymentRepository;
        $this->translator = $translator;
        $this->businessTimePackageService = $businessTimePackageService;
        $this->businessService = $businessService;
    }

    public function searchPaymentsByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->searchPaymentsByBusiness($business, $searchCriteria);
    }

    public function getTotalPaymentsByBusiness(Business $business)
    {
        return $this->businessPaymentRepository->getTotalPaymentsByBusiness($business);
    }

    public function addPenaltyOrExtra($business, $amount, $reason, $fleet, $paymentType)
    {
        if (is_nan($amount)) {
            throw new InvalidFormDataException($this->translator->translate("Importo non valido"));
        }

        if (is_null($business)) {
            throw new InvalidFormDataException($this->translator->translate("Azienda non trovata"));
        }

        $amount = floor($amount * 100);
        $businessPayment = new ExtraPayment(
            $business,
            $reason,
            $amount,
            'EUR'
        );

        $businessPayment->setFleet($fleet);
        $businessPayment->setPaymentType($paymentType);
        $businessPayment->setInvoiceAble(true);

        $this->entityManager->persist($businessPayment);
        $this->entityManager->flush();
    }

    public function countFilteredPaymentsByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->searchPaymentsByBusiness($business, $searchCriteria, true);
    }

    public function flagPaymentAsExpectedPayedByWire($className, $id)
    {
        $payment = $this->businessPaymentRepository->getPaymentByClassAndId($className, $id);
        $payment->flagAsExpectedPayed();
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }

    public function flagPaymentAsConfirmedPayedByWire($className, $id)
    {
        $payment = $this->businessPaymentRepository->getPaymentByClassAndId($className, $id);
        $payment->confirmPayed();
        if ($payment instanceof TimePackagePayment) {
            $this->businessTimePackageService->enableTimePackage(
                $payment->getBusiness(),
                $payment->getTimePackage()
            );
        }
        if ($payment instanceof SubscriptionPayment) {
            $this->businessService->approveEmployeesWaitingForBusinessEnabling($payment->getBusiness());
        }

        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }

    public function getReportData(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->getPaymentReportData($business, $searchCriteria);
    }

    public function getReportTotal(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->getPaymentReportData($business, $searchCriteria, true);
    }

    public function getBusinessSubscriptionPayment(Business $business)
    {
        return $this->businessPaymentRepository->getBusinessSubscriptionPayment($business);
    }

    public function getBusinessExtraPaymentCreditCardChange(Business $business)
    {
        return $this->businessPaymentRepository->getBusinessExtraPaymentCreditCardChange($business);
    }

    public function getPendingBusinessTripPayments(Business $business)
    {
        return $this->businessPaymentRepository->getPendingBusinessTripPayments($business);
    }

    public function getTripPaymentsToBeInvoiced(Business $business)
    {
        return $this->businessPaymentRepository->getTripPaymentsToBeInvoiced($business);
    }

    public function getExtraPaymentsToBeInvoiced(Business $business)
    {
        return $this->businessPaymentRepository->getExtraPaymentsToBeInvoiced($business);
    }

    public function getTimePackagePaymentsToBeInvoiced(Business $business)
    {
        return $this->businessPaymentRepository->getTimePackagePaymentsToBeInvoiced($business);
    }

    public function getPendingBusinessExtraPayments(Business $business)
    {
        return $this->businessPaymentRepository->getPendingBusinessExtraPayments($business);
    }

    public function getSubscriptionPaymentToBeInvoiced(Business $business)
    {
        return $this->businessPaymentRepository->getSubscriptionPaymentToBeInvoiced($business);
    }

    public function manageChangeInBusinessSubscriptionFee(Business $business, $newAmount)
    {
        $payment = $this->businessPaymentRepository->getBusinessSubscriptionPayment($business);
        if ($payment->getAmount() !== $newAmount && (!$payment->isPayed() && !$payment->isExpectedPayed())) {
            $payment->setAmount($newAmount);
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
        }
    }
}
