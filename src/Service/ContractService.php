<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessContract;
use BusinessCore\Entity\Repository\BusinessContractRepository;
use Doctrine\ORM\EntityManager;
use MvlabsPayments\Contract\Contract;
use MvlabsPayments\CustomerContract;

class ContractService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var BusinessContractRepository
     */
    private $contractRepository;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessContractRepository $contractRepository
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessContractRepository $contractRepository
    ) {
        $this->entityManager = $entityManager;
        $this->contractRepository = $contractRepository;
    }

    public function createContract(CustomerContract $customerContract, Business $business)
    {
        $contract = new BusinessContract($business);
        $this->entityManager->persist($contract);
        $this->entityManager->flush();
        $customerContract->customer()->setContract($contract->getPaymentContract());
    }

    public function findById($contractId)
    {
        //return $this->contractRepository->findOneBy(['id' => $contractId]);
        return $this->contractRepository->findBusinessContractById($contractId);
    }
}
