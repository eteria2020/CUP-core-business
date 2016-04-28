<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\Repository\BusinessEmployeeRepository;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Entity\Repository\BusinessTripRepository;
use BusinessCore\Form\InputData\BusinessConfigParams;
use BusinessCore\Form\InputData\BusinessDetails;
use BusinessCore\Service\Helper\SearchCriteria;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class BusinessTripService
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var BusinessRepository
     */

    private $businessRepository;
    /**
     * @var EntityManager
     */

    private $entityManager;
    /**
     * @var BusinessEmployeeRepository
     */
    private $businessEmployeeRepository;
    /**
     * @var BusinessTripRepository
     */
    private $businessTripRepository;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessRepository $businessRepository
     * @param BusinessEmployeeRepository $businessEmployeeRepository
     * @param BusinessTripRepository $businessTripRepository
     * @param $translator
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessRepository $businessRepository,
        BusinessEmployeeRepository $businessEmployeeRepository,
        BusinessTripRepository $businessTripRepository,
        $translator
    ) {
        $this->translator = $translator;
        $this->businessRepository = $businessRepository;
        $this->entityManager = $entityManager;
        $this->businessEmployeeRepository = $businessEmployeeRepository;
        $this->businessTripRepository = $businessTripRepository;
    }

    public function searchTripsByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        return $this->businessTripRepository->searchTripsByBusiness($business, $searchCriteria);
    }

    public function getTotalTripsByBusiness(Business $business)
    {
        return $this->businessTripRepository->countAllByBusiness($business);
    }
}
