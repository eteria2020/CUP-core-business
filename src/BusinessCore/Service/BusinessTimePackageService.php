<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessPayment;
use BusinessCore\Entity\BusinessTimePackage;
use BusinessCore\Entity\Repository\TimePackageRepository;
use BusinessCore\Entity\TimePackage;

use BusinessCore\Entity\TimePackagePayment;
use BusinessCore\Payments\BusinessPaymentRequest;
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
     * @var MockExternalPaymentService
     */
    private $mockExternalPaymentService;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param TimePackageRepository $timePackageRepository
     * @param MockExternalPaymentService $mockExternalPaymentService
     */
    public function __construct(
        EntityManager $entityManager,
        TimePackageRepository $timePackageRepository,
        MockExternalPaymentService $mockExternalPaymentService
    ) {
        $this->entityManager = $entityManager;
        $this->timePackageRepository = $timePackageRepository;
        $this->mockExternalPaymentService = $mockExternalPaymentService;
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

        $timePackagePayment = new TimePackagePayment(
            $business,
            $timePackage,
            $timePackage->getCost(),
            $timePackage->getCurrency()
        );

        $this->entityManager->persist($timePackagePayment);
        $this->entityManager->flush();

        $businessPaymentRequest = new BusinessPaymentRequest($business, [$timePackagePayment]);

        $this->mockExternalPaymentService->pay($businessPaymentRequest);
    }

    public function enableTimePackage(Business $business, TimePackage $timePackage)
    {
        $businessTimePackage = new BusinessTimePackage($business, $timePackage);
        $this->entityManager->persist($businessTimePackage);
        $this->entityManager->flush();
    }
}
