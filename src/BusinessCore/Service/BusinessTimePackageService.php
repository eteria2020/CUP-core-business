<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessPayment;
use BusinessCore\Entity\BusinessTimePackage;
use BusinessCore\Entity\Repository\TimePackageRepository;
use BusinessCore\Entity\TimePackage;

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
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param TimePackageRepository $timePackageRepository
     */
    public function __construct(
        EntityManager $entityManager,
        TimePackageRepository $timePackageRepository
    ) {
        $this->entityManager = $entityManager;
        $this->timePackageRepository = $timePackageRepository;
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
        $this->entityManager->beginTransaction();
        try {
            /** @var TimePackage $timePackage */
            $timePackage = $this->timePackageRepository->find($timePackageId);
            $businessTimePackage = new BusinessTimePackage($business, $timePackage);

            $businessPayment = new BusinessPayment(
                $business,
                $timePackage->getCost(),
                $timePackage->getCurrency(),
                BusinessPayment::TIME_PACKAGE_TYPE
            );

            $this->entityManager->persist($businessTimePackage);
            $this->entityManager->persist($businessPayment);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
