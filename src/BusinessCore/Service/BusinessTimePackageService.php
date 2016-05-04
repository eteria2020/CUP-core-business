<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
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
     * @return TimePackage[]
     */
    public function getBuyablePackages()
    {
        return $this->timePackageRepository->findAll();
    }

    public function buyTimePackage(Business $business, $timePackageId)
    {
        //@todo payment
        $timePackage = $this->timePackageRepository->find($timePackageId);
        $businessTimePackage = new BusinessTimePackage($business, $timePackage);

        $this->entityManager->persist($businessTimePackage);
        $this->entityManager->flush();
    }
}
