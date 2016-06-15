<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\Employee;
use BusinessCore\Entity\BusinessFare;
use BusinessCore\Entity\Repository\BusinessEmployeeRepository;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Entity\Repository\EmployeeRepository;
use BusinessCore\Exception\EmployeeAlreadyAssociatedToDifferentBusinessException;
use BusinessCore\Exception\EmployeeAlreadyAssociatedToThisBusinessException;
use BusinessCore\Exception\EmployeeDeletedException;
use BusinessCore\Entity\Repository\FareRepository;
use BusinessCore\Exception\InvalidFormDataException;
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
     * @var FareRepository
     */
    private $fareRepository;
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
     * @param FareRepository $fareRepository
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessRepository $businessRepository,
        BusinessEmployeeRepository $businessEmployeeRepository,
        EmployeeRepository $employeeRepository,
        Translator $translator,
        EventManager $eventManager,
        FareRepository $fareRepository
    ) {
        $this->translator = $translator;
        $this->businessRepository = $businessRepository;
        $this->entityManager = $entityManager;
        $this->businessEmployeeRepository = $businessEmployeeRepository;
        $this->employeeRepository = $employeeRepository;
        $this->eventManager = $eventManager;
        $this->fareRepository = $fareRepository;
    }

    public function getTotalBusinesses()
    {
        return $this->businessRepository->countAll();
    }

    public function findBySearchValue($value)
    {
        return $this->businessRepository->findBySearchValue($value);
    }

    public function searchBusinesses(SearchCriteria $searchCriteria)
    {
        return $this->businessRepository->searchBusinesses($searchCriteria);
    }

    public function addBusiness(BusinessDetails $businessDetails, BusinessConfigParams $businessParams)
    {
        $this->entityManager->beginTransaction();
        try {
            $code = $this->getUniqueCode();
            $business = Business::fromBusinessDetailsAndParams($code, $businessDetails, $businessParams);
            //get the base fare, there is only one for now
            $baseFare = $this->fareRepository->findOne();
            $businessFare = new BusinessFare($business, $baseFare);

            $this->entityManager->persist($business);
            $this->entityManager->persist($businessFare);

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (UniqueConstraintViolationException $e) {
            $this->entityManager->rollback();
            throw new \Exception($this->translator->translate("Errore di duplicazione codice azienda"));
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
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

        $employee = $this->employeeRepository->find($employeeId);
        $this->eventManager->trigger('employeeApproved', $this, [
            'employee' => $employee
        ]);
    }

    public function approveEmployeeWithBusinessNotEnabled(Business $business, $employeeId)
    {
        $this->setEmployeeStatus($business, $employeeId, BusinessEmployee::STATUS_APPROVED_WAITING_BUSINESS_ENABLING);
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

    public function newBusinessFare(Business $business, $motionDiscount, $parkDiscount)
    {
        if ($motionDiscount < 0 || $motionDiscount > 100 || $parkDiscount < 0 || $parkDiscount > 100) {
            throw new InvalidFormDataException();
        }
        $baseFare = $this->fareRepository->findOne();
        $businessFare = new BusinessFare($business, $baseFare, $motionDiscount, $parkDiscount);

        $this->entityManager->persist($businessFare);
        $this->entityManager->flush();
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

    public function approveEmployeesWaitingForBusinessEnabling(Business $business)
    {
        $waitingEmployees = $business->getApprovedBusinessEmployeeWaitingForBusinessEnabling();
        foreach ($waitingEmployees as $businessEmployee) {
            $this->approveEmployee($business, $businessEmployee->getEmployee()->getId());
        }
    }

    public function getBusinessStatsData($filters)
    {
        $businessName = !empty($filters['filters']['business']) ? $filters['filters']['business'] : null;
        $from = !empty($filters['filters']['from']) ? $filters['filters']['from'] : null;
        $to = !empty($filters['filters']['to']) ? $filters['filters']['to'] : null;

        $labels = [];
        $data = [];

        if (empty($businessName)) {
            $stats = $this->businessRepository->getBusinessStatsData($from, $to);
            foreach ($stats as $row) {
                $labels[] = $row['name'];
                $data[] = $row['minutes'];
            }
        } else {
            $stats = $this->businessRepository->getBusinessGroupStatsData($businessName, $from, $to);
            foreach ($stats as $row) {
                $groupName = empty($row['name']) ? $this->translator->translate('Senza gruppo') : $row['name'];
                $labels[] = $groupName;
                $data[] = $row['minutes'];
            }
        }

        return [
            "labels" => $labels,
            "data" =>  $data
        ];
    }
}
