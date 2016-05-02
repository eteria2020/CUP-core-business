<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessTimePackage;
use BusinessCore\Entity\Repository\TimePackageRepository;
use BusinessCore\Entity\TimePackage;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class BusinessTimePackageService
{
    /**
     * @var Translator
     */
    private $translator;

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
     * @param $translator
     */
    public function __construct(
        EntityManager $entityManager,
        TimePackageRepository $timePackageRepository,
        $translator
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
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
        //@todo process payment
        $timePackage = $this->timePackageRepository->find($timePackageId);
        $businessTimePackage = new BusinessTimePackage($business, $timePackage);

        $this->entityManager->persist($businessTimePackage);
        $this->entityManager->flush();
    }
}
