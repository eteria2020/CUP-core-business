<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessFleet;
use BusinessCore\Entity\BusinessInvoice;
use BusinessCore\Entity\BusinessTripPayment;
use BusinessCore\Entity\ExtraPayment;
use BusinessCore\Entity\Repository\BusinessInvoiceRepository;

use BusinessCore\Entity\SubscriptionPayment;
use BusinessCore\Entity\TimePackagePayment;
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
     * @var array
     */
    private $invoiceConfig;

    /**
     * BusinessInvoiceService constructor.
     * @param EntityManager $entityManager
     * @param BusinessInvoiceRepository $businessInvoiceRepository
     * @param array $invoiceConfig
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessInvoiceRepository $businessInvoiceRepository,
        array $invoiceConfig
    ) {
        $this->entityManager = $entityManager;
        $this->businessInvoiceRepository = $businessInvoiceRepository;
        $this->invoiceConfig = $invoiceConfig;
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

    /**
     * @param Business $business
     * @param BusinessTripPayment[] $tripPayments
     * @return BusinessInvoice
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function createInvoiceForTrips(Business $business, array $tripPayments)
    {
        $invoice = $this->prepareInvoiceForTrips($business, $tripPayments);
        $this->entityManager->persist($invoice);
        foreach ($tripPayments as $tripPayment) {
            $tripPayment->setBusinessInvoice($invoice);
            $this->entityManager->persist($tripPayment);
        }

        $this->entityManager->flush();

        return $invoice;
    }

    /**
     * @param Business $business
     * @param BusinessTripPayment[] $tripPayments
     * @return BusinessInvoice
     */
    public function prepareInvoiceForTrips(
        Business $business,
        array $tripPayments
    ) {
        $vat = $this->invoiceConfig['vat_percentage'];
        $templateVersion = $this->invoiceConfig['template_version'];
        $rowAmounts = [];
        $total = 0;
        // calculate amounts for single rows and add them to total
        /** @var BusinessTripPayment $tripPayment */
        foreach ($tripPayments as $tripPayment) {
            $rowAmounts[] = $this->parseDecimal($tripPayment->getAmount());
            $total += $tripPayment->getAmount();
        }

        $invoiceNumber = $this->getNewInvoiceNumber($business->getFleet());
        return BusinessInvoice::createInvoiceForTrips(
            $business,
            $invoiceNumber,
            $tripPayments,
            $templateVersion,
            [
                'sum' => $this->calculateAmountsWithTaxesFromTotal($total),
                'rows' => $rowAmounts,
                'vat' => $vat
            ]
        );
    }

    /**
     * @param integer $amount
     * @return array
     */
    public function calculateAmountsWithTaxesFromTotal($amount)
    {
        $amounts = [];
        $vatPercent = $this->invoiceConfig['vat_percentage'];
        // calculate amounts
        $vat = (integer) ($amount / (100 + $vatPercent) * $vatPercent);
        $total = $amount - $vat;

        // format amounts
        $amounts['vat'] = $this->parseDecimal($vat);
        $amounts['total'] = $this->parseDecimal($total);
        $amounts['grand_total'] = $this->parseDecimal($amount);

        $amounts['grand_total_cents'] = $amount;

        return $amounts;
    }

    /**
     * @param integer
     * @return string
     */
    private function parseDecimal($decimal)
    {
        return number_format((float) $decimal / 100, 2, ',', '');
    }

    private function getNewInvoiceNumber(BusinessFleet $fleet)
    {
        return $this->businessInvoiceRepository->getNewInvoiceNumber($fleet);
    }

    /**
     * @param Business $business
     * @param ExtraPayment[] $extraPayments
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function createInvoiceForExtras(Business $business, array $extraPayments)
    {
        $invoice = $this->prepareInvoiceForExtras($business, $extraPayments);
        $this->entityManager->persist($invoice);
        foreach ($extraPayments as $extraPayment) {
            $extraPayment->setBusinessInvoice($invoice);
            $this->entityManager->persist($extraPayment);
        }

        $this->entityManager->flush();

        return $invoice;
    }

    /**
     * @param Business $business
     * @param ExtraPayment[] $extraPayments
     * @return BusinessInvoice
     */
    public function prepareInvoiceForExtras(
        Business $business,
        array $extraPayments
    ) {
        $vat = $this->invoiceConfig['vat_percentage'];
        $templateVersion = $this->invoiceConfig['template_version'];
        $rowAmounts = [];
        $total = 0;
        // calculate amounts for single rows and add them to total
        foreach ($extraPayments as $extraPayment) {
            $rowAmounts[] = $this->parseDecimal($extraPayment->getAmount());
            $total += $extraPayment->getAmount();
        }

        $invoiceNumber = $this->getNewInvoiceNumber($business->getFleet());
        return BusinessInvoice::createInvoiceForExtraOrPenalty(
            $business,
            $invoiceNumber,
            $extraPayments,
            $templateVersion,
            [
                'sum' => $this->calculateAmountsWithTaxesFromTotal($total),
                'rows' => $rowAmounts,
                'vat' => $vat
            ]
        );
    }

    /**
     * @param $business
     * @param TimePackagePayment[] $packagePayements
     * @return BusinessInvoice
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function createInvoiceForTimePackages(Business $business, array $packagePayements)
    {
        $invoice = $this->prepareInvoiceForTimePackages($business, $packagePayements);
        $this->entityManager->persist($invoice);
        foreach ($packagePayements as $packagePayement) {
            $packagePayement->setBusinessInvoice($invoice);
            $this->entityManager->persist($packagePayement);
        }

        $this->entityManager->flush();

        return $invoice;
    }

    /**
     * @param $business
     * @param TimePackagePayment[] $packagePayements
     * @return BusinessInvoice
     */
    private function prepareInvoiceForTimePackages(Business $business, array $packagePayements)
    {
        $vat = $this->invoiceConfig['vat_percentage'];
        $templateVersion = $this->invoiceConfig['template_version'];
        $rowAmounts = [];
        $total = 0;
        // calculate amounts for single rows and add them to total
        foreach ($packagePayements as $packagePayement) {
            $rowAmounts[] = $this->parseDecimal($packagePayement->getAmount());
            $total += $packagePayement->getAmount();
        }

        $invoiceNumber = $this->getNewInvoiceNumber($business->getFleet());
        return BusinessInvoice::createInvoiceForTimePackage(
            $business,
            $invoiceNumber,
            $packagePayements,
            $templateVersion,
            [
                'sum' => $this->calculateAmountsWithTaxesFromTotal($total),
                'rows' => $rowAmounts,
                'vat' => $vat
            ]
        );
    }

    /**
     * @param Business $business
     * @param SubscriptionPayment[] $subscriptionPayments
     * @return BusinessInvoice
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function createInvoiceForSubscription(Business $business, array $subscriptionPayments)
    {
        $invoice = $this->prepareInvoiceForSubscription($business, $subscriptionPayments);
        $this->entityManager->persist($invoice);
        foreach ($subscriptionPayments as $subscriptionPayment) {
            $subscriptionPayment->setBusinessInvoice($invoice);
            $this->entityManager->persist($subscriptionPayment);
        }

        $this->entityManager->flush();

        return $invoice;
    }

    /**
     * @param $business
     * @param SubscriptionPayment[] $subscriptionPayments
     * @return BusinessInvoice
     */
    private function prepareInvoiceForSubscription(Business $business, array $subscriptionPayments)
    {
        $vat = $this->invoiceConfig['vat_percentage'];
        $templateVersion = $this->invoiceConfig['template_version'];
        $rowAmounts = [];
        $total = 0;
        // calculate amounts for single rows and add them to total
        foreach ($subscriptionPayments as $subscriptionPayment) {
            $rowAmounts[] = $this->parseDecimal($subscriptionPayment->getAmount());
            $total += $subscriptionPayment->getAmount();
        }

        $invoiceNumber = $this->getNewInvoiceNumber($business->getFleet());
        return BusinessInvoice::createInvoiceForSubscription(
            $business,
            $invoiceNumber,
            $subscriptionPayments,
            $templateVersion,
            [
                'sum' => $this->calculateAmountsWithTaxesFromTotal($total),
                'rows' => $rowAmounts,
                'vat' => $vat
            ]
        );
    }

    /**
     * @param BusinessFleet | null $fleet
     * @return BusinessInvoice[]
     */
    public function getInvoicesByFleetJoinBusiness($fleet = null)
    {
        return $this->businessInvoiceRepository->findInvoicesByFleetJoinCustomers($fleet);
    }


    /**
     * @param \DateTime $date
     * @param BusinessFleet | null $fleet
     * @return BusinessInvoice[]
     */
    public function getInvoicesByDateAndFleetJoinBusiness(\DateTime $date, $fleet = null)
    {
        return $this->businessInvoiceRepository->findInvoicesByDateAndFleetJoinCustomers($date, $fleet);
    }

    public function groupByInvoiceDate($invoices)
    {
        $groupedInvoices = [];
        /** @var BusinessInvoice[] $invoices */
        foreach ($invoices as $invoice) {
            if (array_key_exists($invoice->getInvoiceDate(), $groupedInvoices)) {
                array_push($groupedInvoices[$invoice->getInvoiceDate()], $invoice);
            } else {
                $groupedInvoices[$invoice->getInvoiceDate()] = [$invoice];
            }
        }
        return $groupedInvoices;
    }

    /**
     * @param BusinessInvoice $invoice
     * @return string
     */
    public function getExportDataForInvoice($invoice)
    {
        // get the dates depending on the type of invoice
        $period = $invoice->getInterval();
        $business = $invoice->getBusiness();

        $paymentType = "CC101";     // default is credit card
        if($business->getPaymentType()==="wire_transfer"){
            $paymentType ="BB101";
        }

        // generate the first common part between the two records
        $partionRecord1 = [
            "110",                                          // 2. 11
            $invoice->getDateTimeDate()->format("d/m/Y"),   // 3. 10
            substr($invoice->getInvoiceNumber(), 5),        // 4. 20
            "TC",                                           // 5. 30
            $invoice->getDateTimeDate()->format("d/m/Y"),   // 6. 50
            substr($invoice->getInvoiceNumber(), 5),        // 7. 61
            "",                                             // 8. 130
            $business->getCode(),                           // 9. 78
            $paymentType,                                   // 10. 241
            $invoice->getAmount(),                          // 11. 140
        ];

        // generate the second common part between the two records
        $partionRecord2 = [
            $invoice->getAmount(),                          // 14. 930
            $invoice->getVat(),                             // 15. 1001
            $period->start()->format("d/m/Y"),              // 16. 1020
            $period->end()->format("d/m/Y"),                // 17. 1030
            "FR"                                            // 18. 99999
        ];

        // generate the first record
        $rowTES = array_merge(
            ["TES"],                                        // 1. 3
            $partionRecord1,                                // 2. - 11 .
            [""], // 660                                    // 12.
            [""], // 681                                    // 13.
            $partionRecord2                                 // 14. -- 18 .
        );

        // generate the second record
        $rowRIG = array_merge(
            ["RIG"],                                        // 1. 3
            $partionRecord1,                                // 2. - 11 .
            ["40"],                                         // 12. 660
            [strtoupper($invoice->getTypeItalianTranslation())], // 13. 681
            $partionRecord2                                 // 14. -- 18 .
        );

        // return the two records combined
        return implode(";", $rowTES) . "\r\n" . implode(";", $rowRIG);
    }

}
