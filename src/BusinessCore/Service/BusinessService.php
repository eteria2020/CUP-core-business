<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\Employee;
use BusinessCore\Entity\Repository\BusinessEmployeeRepository;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Entity\Repository\EmployeeRepository;
use BusinessCore\Exception\EmployeeAlreadyAssociatedToDifferentBusinessException;
use BusinessCore\Exception\EmployeeAlreadyAssociatedToThisBusinessException;
use BusinessCore\Exception\EmployeeDeletedException;
use BusinessCore\Form\InputData\BusinessConfigParams;
use BusinessCore\Form\InputData\BusinessDetails;
use BusinessCore\Service\Helper\SearchCriteria;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Zend\EventManager\EventManager;
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
     * @var EmployeeRepository
     */
    private $employeeRepository;
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessRepository $businessRepository
     * @param BusinessEmployeeRepository $businessEmployeeRepository
     * @param EmployeeRepository $employeeRepository
     * @param Translator $translator
     * @param EventManager $eventManager
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessRepository $businessRepository,
        BusinessEmployeeRepository $businessEmployeeRepository,
        EmployeeRepository $employeeRepository,
        Translator $translator,
        EventManager $eventManager
    ) {
        $this->translator = $translator;
        $this->businessRepository = $businessRepository;
        $this->entityManager = $entityManager;
        $this->businessEmployeeRepository = $businessEmployeeRepository;
        $this->employeeRepository = $employeeRepository;
        $this->eventManager = $eventManager;
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
        $businessEmployee = $this->businessEmployeeRepository->find(
            [
                'employee' => $employeeId,
                'business' => $business
            ]
        );
        $this->entityManager->remove($businessEmployee);
        $this->entityManager->flush();
    }

    public function approveEmployee(Business $business, $employeeId)
    {
        $this->setEmployeeStatus($business, $employeeId, BusinessEmployee::STATUS_APPROVED);

        $employee = $this->employeeRepository->find($employeeId);
        $this->eventManager->trigger('employeeApproved', $this, [
            'employee' => $employee
        ]);
    }

    public function blockEmployee(Business $business, $employeeId)
    {
        $this->setEmployeeStatus($business, $employeeId, BusinessEmployee::STATUS_BLOCKED);
    }

    public function unblockEmployee(Business $business, $employeeId)
    {
        $this->setEmployeeStatus($business, $employeeId, BusinessEmployee::STATUS_APPROVED);
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

    public function associateEmployeeToBusinessByAssociationCode($employeeId, $associationCode)
    {
        /** @var Business $business */
        $business = $this->businessRepository->findOneBy(['associationCode' => $associationCode]);

        if (!$business instanceof Business) {
            throw new EntityNotFoundException();
        }
        /** @var Employee $employee */
        $employee = $this->employeeRepository->findOneById($employeeId);

        $businessEmployee = $this->businessEmployeeRepository->findOneBy(['employee' => $employee, 'business' => $business]);
        if ($businessEmployee instanceof BusinessEmployee) {
            if ($businessEmployee->getStatus() == BusinessEmployee::STATUS_DELETED) {
                throw new EmployeeDeletedException();
            } else {
                throw new EmployeeAlreadyAssociatedToThisBusinessException();
            }
        }
        $businessEmployee = $this->businessEmployeeRepository->findActiveAssociation($employee);

        if ($businessEmployee instanceof BusinessEmployee) {
            throw new EmployeeAlreadyAssociatedToDifferentBusinessException();
        } else {
            $businessEmployee = new BusinessEmployee($employee, $business);
            $this->entityManager->persist($businessEmployee);
            $this->entityManager->flush();
            $this->eventManager->trigger('newEmployeeAssociated', $this, [
                'employee' => $employee
            ]);

            if ($businessEmployee->isApproved()) {
                $this->entityManager->detach($employee); //clear doctrine cached entity
                $employee = $this->employeeRepository->findOneById($employeeId);
                $this->eventManager->trigger('employeeApproved', $this, [
                    'employee' => $employee
                ]);
            }
        }
    }
}
