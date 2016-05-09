<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Repository\BusinessPaymentRepository;

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

    /**
     * @param Business $business
     * @return \BusinessCore\Entity\TimePackage[]
     */
    public function findAll(Business $business)
    {
        return $this->businessPaymentRepository->findBy(['business' =>$business]);
    }

    public function searchPaymentsByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessPaymentRepository->searchPaymentsByBusiness($business, $searchCriteria);
    }

    public function getTotalPaymentsByBusiness(Business $business)
    {
        return $this->businessPaymentRepository->getTotalPaymentsByBusiness($business);
    }
}
