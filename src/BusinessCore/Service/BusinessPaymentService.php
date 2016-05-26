<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessPayment;
use BusinessCore\Entity\Repository\BusinessPaymentRepository;
use BusinessCore\Exception\InvalidFormDataException;
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

    public function addPenaltyOrExtra(Business $business, $amount, $type)
    {
        if (is_nan($amount) || ($type != BusinessPayment::PENALTY_TYPE && $type != BusinessPayment::EXTRA_TYPE)) {
            throw new InvalidFormDataException();
        }
        $amount = floor($amount * 100);
        $businessPayment = new BusinessPayment(
            $business,
            $amount,
            'EUR',
            $type
        );

        $this->entityManager->persist($businessPayment);
        $this->entityManager->flush();
    }
}
