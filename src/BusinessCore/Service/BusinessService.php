<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Form\InputData\BusinessDetails;
use BusinessCore\Form\InputData\BusinessParams;
use BusinessCore\Service\Helper\SearchCriteria;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class BusinessService
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
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessRepository $businessRepository
     * @param $translator
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessRepository $businessRepository,
        $translator
    ) {
        $this->translator = $translator;
        $this->businessRepository = $businessRepository;
        $this->entityManager = $entityManager;
    }

    public function getTotalBusinesses()
    {
        return $this->businessRepository->countAll();
    }

    public function searchBusinesses(SearchCriteria $searchCriteria)
    {
        return $this->businessRepository->searchBusinesses($searchCriteria);
    }

    public function addBusiness(BusinessDetails $businessData, BusinessParams $businessParams)
    {
        $code = $this->getUniqueCode();
        $business = Business::fromBusinessDataAndParams($code, $businessData, $businessParams);

        try {
            $this->entityManager->persist($business);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception($this->translator->translate("Errore di duplicazione codice azienda"));
        }
        return $business;
    }

    public function updateBusiness(Business $business, $data)
    {
        $business->update($data);

        $this->entityManager->persist($business);
        $this->entityManager->flush();
        return $business;
    }

    /**
     * @param $code
     * @return Business
     */
    public function getBusinessByCode($code)
    {
        return $this->businessRepository->getBusinessByCode($code);
    }

    public function removeEmployee($businessCode, $employeeId)
    {
        return $this->businessRepository->removeEmployee($businessCode, $employeeId);
    }

    public function blockEmployee($businessCode, $employeeId)
    {
        return $this->businessRepository->setEmployeeStatus($businessCode, $employeeId, BusinessEmployee::STATUS_BLOCKED);
    }

    public function approveEmployee($businessCode, $employeeId)
    {
        return $this->businessRepository->setEmployeeStatus($businessCode, $employeeId, BusinessEmployee::STATUS_APPROVED);
    }

    public function getUniqueCode()
    {
        $code = substr(md5(uniqid(rand(), true)), 0, 6);
        while ($this->businessRepository->getBusinessByCode($code) != null) {
            $code = substr(md5(uniqid(rand(), true)), 0, 6);
        }
        return $code;
    }
}
