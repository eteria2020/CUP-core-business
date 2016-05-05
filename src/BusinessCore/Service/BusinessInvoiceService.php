<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\BusinessInvoice;
use BusinessCore\Entity\Repository\BusinessEmployeeRepository;
use BusinessCore\Entity\Repository\BusinessInvoiceRepository;
use BusinessCore\Entity\Repository\BusinessRepository;
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

    public function searchInvoicesByBusiness($business, $searchCriteria)
    {
        return $this->businessInvoiceRepository->searchInvoicesByBusiness($business, $searchCriteria);
    }

    public function getTotalInvoicesByBusiness($business)
    {
        return $this->businessInvoiceRepository->countAllByBusiness($business);
    }

    /**
     * @param $invoiceId
     * @param $business
     * @return BusinessInvoice
     */
    public function findOneByIdAndBusiness($invoiceId, $business)
    {
        return $this->businessInvoiceRepository->findOneBy([
                'id' => $invoiceId,
                'business' => $business
        ]);
    }
}
