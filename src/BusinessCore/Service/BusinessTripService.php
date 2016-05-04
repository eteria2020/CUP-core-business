<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Repository\BusinessTripRepository;
use BusinessCore\Service\Helper\SearchCriteria;

class BusinessTripService
{
    /**
     * @var BusinessTripRepository
     */
    private $businessTripRepository;

    /**
     * BusinessService constructor.
     * @param BusinessTripRepository $businessTripRepository
     */
    public function __construct(BusinessTripRepository $businessTripRepository)
    {
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
