<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessTimePackage;
use BusinessCore\Entity\Repository\TimePackageRepository;
use BusinessCore\Entity\TimePackage;

use BusinessCore\Entity\TimePackagePayment;
use BusinessCore\Payment\BusinessPaymentRequest;
use Doctrine\ORM\EntityManager;

class BusinessTimePackageService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TimePackageRepository
     */
    private $timePackageRepository;
    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param TimePackageRepository $timePackageRepository
     * @param PaymentService $paymentService
     */
    public function __construct(
        EntityManager $entityManager,
        TimePackageRepository $timePackageRepository,
        PaymentService $paymentService
    ) {
        $this->entityManager = $entityManager;
        $this->timePackageRepository = $timePackageRepository;
        $this->paymentService = $paymentService;
    }

    /**
     * @param Business $business
     * @return \BusinessCore\Entity\TimePackage[]
     */
    public function findBuyablePackages(Business $business)
    {
        return $this->timePackageRepository->findBuyableByBusiness($business);
    }

    public function buyTimePackage(Business $business, $timePackageId)
    {
        /** @var TimePackage $timePackage */
        $timePackage = $this->timePackageRepository->find($timePackageId);

        if ($business->canBuyTimePackage($timePackage)) {
            $timePackagePayment = new TimePackagePayment(
                $business,
                $timePackage,
                $timePackage->getCost(),
                $timePackage->getCurrency()
            );

            $this->entityManager->persist($timePackagePayment);
            $this->entityManager->flush();
            if ($business->payWithCreditCard()) {
                $customer = $business->getPaymentCustomer();
                $businessPaymentRequest = new BusinessPaymentRequest($customer, [$timePackagePayment]);

                $this->paymentService->pay($businessPaymentRequest);
            }
        } else {
            throw new \Exception();
        }
    }

    public function enableTimePackage(Business $business, TimePackage $timePackage)
    {
        $businessTimePackage = new BusinessTimePackage($business, $timePackage);
        $this->entityManager->persist($businessTimePackage);
        $this->entityManager->flush();
    }
}
