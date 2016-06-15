<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessBuyableTimePackage;
use BusinessCore\Entity\BusinessPayment;
use BusinessCore\Entity\BusinessTimePackage;
use BusinessCore\Entity\Repository\TimePackageRepository;
use BusinessCore\Entity\TimePackage;

use BusinessCore\Exception\InvalidBusinessFormException;
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
