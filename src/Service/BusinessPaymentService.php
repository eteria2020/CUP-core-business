<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Entity\Repository\BusinessPaymentRepository;
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
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessPaymentRepository $businessPaymentRepository
     * @param Translator $translator
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessPaymentRepository $businessPaymentRepository,
        Translator $translator
    ) {
        $this->entityManager = $entityManager;
        $this->businessPaymentRepository = $businessPaymentRepository;
        $this->translator = $translator;
    }

    public function searchPaymentsByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->searchPaymentsByBusiness($business, $searchCriteria);
    }

    public function getTotalPaymentsByBusiness(Business $business)
    {
        return $this->businessPaymentRepository->getTotalPaymentsByBusiness($business);
    }

    public function addPenaltyOrExtra($business, $amount, $reason)
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

    public function getPendingBusinessTripPayments(Business $business)
    {
        return $this->businessPaymentRepository->getPendingBusinessTripPayments($business);
    }
}
