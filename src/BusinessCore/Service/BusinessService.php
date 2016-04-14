<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Form\InputData\BusinessData;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\I18n\Translator;

class BusinessService
{
    /**
     * @var string website base url
     */
    private $url;

    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var DatatableService
     */
    private $datatableService;
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
     * @param DatatableService $datatableService
     * @param Translator $translator
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessRepository $businessRepository,
        DatatableService $datatableService,
        Translator $translator
    ) {
        $this->translator = $translator;
        $this->businessRepository = $businessRepository;
        $this->entityManager = $entityManager;
        $this->datatableService = $datatableService;
    }

    public function getTotalBusinesses()
    {
        return count($this->businessRepository->findAll());
    }

    public function getDataDataTable(array $filters = [], $count = false)
    {
        $businesses = $this->datatableService->getData('Business', $filters, $count);

        if ($count) {
            return $businesses;
        }

        return array_map(function (Business $business) {
            return [
                'e' => [
                    'name' => $business->getName(),
                    'code' => $business->getCode(),
                    'vatNumber' => $business->getVatNumber(),
                    'domains' => $business->getDomains(),
                    'city' => $business->getCity(),
                    'phone' => $business->getPhone(),
                    'insertedTs' => $business->getInsertedTs()->format('d-m-Y H:i:s'),
                ],
                'button' => $business->getCode()
            ];
        }, $businesses);
    }

    public function addBusiness(BusinessData $businessData)
    {
        $business = new Business($businessData->getCode(), $businessData->getData());

        $this->entityManager->persist($business);
        $this->entityManager->flush();
        return $business;
    }

    public function updateBusiness(Business $business, $data)
    {
        $business->update($data);

        $this->entityManager->persist($business);
        $this->entityManager->flush();
        return $business;
    }

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
        return $this->businessRepository->setEmployeeBlockStatus($businessCode, $employeeId, true);
    }

    public function unblockEmployee($businessCode, $employeeId)
    {
        return $this->businessRepository->setEmployeeBlockStatus($businessCode, $employeeId, false);
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
