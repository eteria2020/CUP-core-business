<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessTrip;
use BusinessCore\Entity\BusinessTripPayment;
use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Entity\Group;
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
     * @param BusinessTripRepository $businessTripRepository
     * @param BusinessPaymentService $businessPaymentService
     * @param PaymentService $paymentService
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessTripRepository $businessTripRepository,
        BusinessPaymentService $businessPaymentService,
        PaymentService $paymentService
    ) {
        $this->entityManager = $entityManager;
        $this->paymentService = $paymentService;
        $this->businessPaymentService = $businessPaymentService;
        $this->businessTripRepository = $businessTripRepository;
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

    /**
     * @param Business $business
     * @param BusinessTripPayment[] $trips
     */
    public function payTrips(Business $business, array $trips)
    {
        $customer = $business->getPaymentCustomer();
        $businessPaymentRequest = new BusinessPaymentRequest($customer, $trips);

        $this->paymentService->pay($businessPaymentRequest);
    }

    /**
     * @param Business $business
     * @param ExtraPayment[] $extras
     */
    public function payExtras(Business $business, array $extras)
    {
        $customer = $business->getPaymentCustomer();
        $businessPaymentRequest = new BusinessPaymentRequest($customer, $extras);

        $this->paymentService->pay($businessPaymentRequest);
    }

    /**
     * @param $tripId
     * @return BusinessTrip
     */
    public function getBusinessTripByTripId($tripId)
    {
        return $this->businessTripRepository->findOneBy(['trip' => $tripId]);
    }

    public function getBusinessTripsByGroup(Group $group, \DateTime $from)
    {
        return $this->businessTripRepository->getBusinessTripByGroup($group, $from);
    }
}
