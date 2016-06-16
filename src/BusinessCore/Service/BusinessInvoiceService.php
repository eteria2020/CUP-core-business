<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessInvoice;
use BusinessCore\Entity\Repository\BusinessInvoiceRepository;

use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityManager;

class BusinessInvoiceService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var BusinessInvoiceRepository
     */
    private $businessInvoiceRepository;

    /**
     * BusinessInvoiceService constructor.
     * @param EntityManager $entityManager
     * @param BusinessInvoiceRepository $businessInvoiceRepository
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessInvoiceRepository $businessInvoiceRepository
    ) {
        $this->entityManager = $entityManager;
        $this->businessInvoiceRepository = $businessInvoiceRepository;
    }

    public function searchInvoicesByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessInvoiceRepository->searchInvoicesByBusiness($business, $searchCriteria);
    }

    public function getTotalInvoicesByBusiness(Business $business)
    {
        return $this->businessInvoiceRepository->countAllByBusiness($business);
    }

    /**
     * @param $invoiceId
     * @param Business $business
     * @return BusinessInvoice
     */
    public function findOneByIdAndBusiness($invoiceId, Business $business)
    {
        return $this->businessInvoiceRepository->findOneBy([
                'id' => $invoiceId,
                'business' => $business
        ]);
    }

    public function countFilteredInvoicesByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessInvoiceRepository->searchInvoicesByBusiness($business, $searchCriteria, true);
    }
}
