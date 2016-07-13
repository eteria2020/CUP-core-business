<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessBuyableTimePackage;
use BusinessCore\Entity\BusinessTimePackage;
use BusinessCore\Entity\Repository\TimePackageRepository;
use BusinessCore\Entity\TimePackage;

use BusinessCore\Exception\InvalidBusinessFormException;
use BusinessCore\Entity\TimePackagePayment;
use BusinessCore\Payments\BusinessPaymentRequest;
use Doctrine\ORM\EntityManager;
use BusinessCore\Service\MockExternalPaymentService as PaymentService;

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

        $timePackagePayment = new TimePackagePayment(
            $business,
            $timePackage,
            $timePackage->getCost(),
            $timePackage->getCurrency()
        );

        $this->entityManager->persist($timePackagePayment);
        $this->entityManager->flush();

        $businessPaymentRequest = new BusinessPaymentRequest($business, [$timePackagePayment]);

        $this->paymentService->pay($businessPaymentRequest);
    }

    public function enableTimePackage(Business $business, TimePackage $timePackage)
    {
        $businessTimePackage = new BusinessTimePackage($business, $timePackage);
        $this->entityManager->persist($businessTimePackage);
        $this->entityManager->flush();
    }

    public function findAll()
    {
        return $this->timePackageRepository->findAll();
    }

    public function addTimePackage($minutes, $cost)
    {
        if (is_nan($minutes) || is_nan($cost) || $minutes < 0 || $cost < 0) {
            throw new InvalidBusinessFormException();
        }
        $cost = floor($cost * 100);

        $timePackage = new TimePackage($minutes, $cost);
        $this->entityManager->persist($timePackage);
        $this->entityManager->flush();
    }

    public function setBuyablePackagesFromIds($buyableIds, Business $business)
    {
        $this->entityManager->beginTransaction();
        try {

            $buyableTimePackages = $this->timePackageRepository->findBy(['id' => $buyableIds]);

            $buyables = $business->getBusinessBuyableTimePackages();

            //remove all buyable packages
            foreach ($buyables as $buyable) {
                $this->entityManager->remove($buyable);
            }
            $this->entityManager->flush();

            /** @var TimePackage $timePackage */
            foreach ($buyableTimePackages as $timePackage) {
                $association = new BusinessBuyableTimePackage($business, $timePackage);
                $this->entityManager->persist($association);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
