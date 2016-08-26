<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Repository\BusinessTripRepository;
use BusinessCore\Payment\BusinessPaymentRequest;
use BusinessCore\Service\Helper\SearchCriteria;

use Doctrine\ORM\EntityManager;

class BusinessTripService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var BusinessTripRepository
     */
    private $businessTripRepository;
    /**
     * @var PaymentService
     */
    private $paymentService;
    /**
     * @var BusinessPaymentService
     */
    private $businessPaymentService;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessPaymentService $businessPaymentService
     * @param PaymentService $paymentService
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessPaymentService $businessPaymentService,
        PaymentService $paymentService
    ) {
        $this->entityManager = $entityManager;
        $this->paymentService = $paymentService;
        $this->businessPaymentService = $businessPaymentService;
    }

    public function searchTripsByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessTripRepository->searchTripsByBusiness($business, $searchCriteria);
    }

    public function getTotalTripsByBusiness(Business $business)
    {
        return $this->businessTripRepository->countAllByBusiness($business);
    }

    public function countFilteredTripsByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessTripRepository->searchTripsByBusiness($business, $searchCriteria, true);
    }

    public function getTripsToBePayed(Business $business)
    {
        return $this->businessPaymentService->getPendingBusinessTripPayments($business);
    }

    public function payTrips(Business $business, array $trips)
    {
        $customer = $business->getPaymentCustomer();
        $businessPaymentRequest = new BusinessPaymentRequest($customer, $trips);

        $this->paymentService->pay($businessPaymentRequest);
    }
}
