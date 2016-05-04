<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\BusinessInvoice;
use BusinessCore\Entity\Repository\BusinessInvoiceRepository;

class BusinessInvoiceService
{
    /**
     * @var BusinessInvoiceRepository
     */
    private $businessInvoiceRepository;

    /**
     * BusinessInvoiceService constructor.
     * @param BusinessInvoiceRepository $businessInvoiceRepository
     */
    public function __construct(
        BusinessInvoiceRepository $businessInvoiceRepository
    ) {
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
                'invoice' => $invoiceId,
                'business' => $business
        ]);
    }
}
