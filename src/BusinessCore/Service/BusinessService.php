<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\Repository\BusinessEmployeeRepository;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Form\InputData\BusinessConfigParams;
use BusinessCore\Form\InputData\BusinessDetails;
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
     * @var BusinessEmployeeRepository
     */
    private $businessEmployeeRepository;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessRepository $businessRepository
     * @param BusinessEmployeeRepository $businessEmployeeRepository
     * @param $translator
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessRepository $businessRepository,
        BusinessEmployeeRepository $businessEmployeeRepository,
        $translator
    ) {
        $this->translator = $translator;
        $this->businessRepository = $businessRepository;
        $this->entityManager = $entityManager;
        $this->businessEmployeeRepository = $businessEmployeeRepository;
    }

    public function getTotalBusinesses()
    {
        return $this->businessRepository->countAll();
    }

    public function searchBusinesses(SearchCriteria $searchCriteria)
    {
        return $this->businessRepository->searchBusinesses($searchCriteria);
    }

    public function addBusiness(BusinessDetails $businessDetails, BusinessConfigParams $businessParams)
    {
        $code = $this->getUniqueCode();
        $business = Business::fromBusinessDetailsAndParams($code, $businessDetails, $businessParams);

        try {
            $this->entityManager->persist($business);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception($this->translator->translate("Errore di duplicazione codice azienda"));
        }
        return $business;
    }

    /**
     * @param $code
     * @return Business
     */
    public function getBusinessByCode($code)
    {
        return $this->businessRepository->findOneBy(['code' => $code]);
    }

    public function removeEmployee(Business $business, $employeeId)
    {
        $this->setEmployeeStatus($business, $employeeId, BusinessEmployee::STATUS_DELETED);
    }

    public function approveEmployee(Business $business, $employeeId)
    {
        $this->setEmployeeStatus($business, $employeeId, BusinessEmployee::STATUS_APPROVED);
    }

    public function blockEmployee(Business $business, $employeeId)
    {
        $this->setEmployeeStatus($business, $employeeId, BusinessEmployee::STATUS_BLOCKED);
    }

    private function setEmployeeStatus(Business $business, $employeeId, $status)
    {
        $businessEmployee = $this->businessEmployeeRepository->find(
            [
                'employee' => $employeeId,
                'business' => $business
            ]
        );
        $businessEmployee->setStatus($status);
        $this->entityManager->persist($businessEmployee);
        $this->entityManager->flush();
    }

    public function updateBusinessDetails(Business $business, BusinessDetails $inputData)
    {
        $business->updateDetails($inputData);

        $this->entityManager->persist($business);
        $this->entityManager->flush();
        return $business;
    }

    public function updateBusinessConfigParams(Business $business, BusinessConfigParams $inputData)
    {
        $business->updateParams($inputData);

        $this->entityManager->persist($business);
        $this->entityManager->flush();
        return $business;
    }

    public function getUniqueCode()
    {
        $code = substr(md5(uniqid(rand(), true)), 0, 6);
        while ($this->businessRepository->findOneBy(['code' => $code]) != null) {
            $code = substr(md5(uniqid(rand(), true)), 0, 6);
        }
        return $code;
    }
}
