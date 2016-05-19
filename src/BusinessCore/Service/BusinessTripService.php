<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Repository\BusinessTripRepository;
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
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessTripRepository $businessTripRepository
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessTripRepository $businessTripRepository
    ) {
        $this->entityManager = $entityManager;
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
}
