<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Base\BusinessPayment;
use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessTripPayment;
use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Entity\Repository\BusinessPaymentRepository;

use BusinessCore\Entity\TimePackagePayment;
use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityManager;

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
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessPaymentRepository $businessPaymentRepository
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessPaymentRepository $businessPaymentRepository
    ) {
        $this->entityManager = $entityManager;
        $this->businessPaymentRepository = $businessPaymentRepository;
    }

    public function searchPaymentsByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->searchPaymentsByBusiness($business, $searchCriteria);
    }

    public function getTotalPaymentsByBusiness(Business $business)
    {
        return $this->businessPaymentRepository->getTotalPaymentsByBusiness($business);
    }

    public function countFilteredPaymentsByBusiness($business, $searchCriteria)
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

    public function getReportData(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->getPaymentReportData($business, $searchCriteria);
    }

    public function getReportTotal(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->getPaymentReportData($business, $searchCriteria, true);
    }
}
