<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessContract;
use Doctrine\ORM\EntityManager;
use MvlabsPayments\CustomerContract;

class ContractService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function createContract(Business $business)
    {
        $contract = new BusinessContract($business);
        $this->entityManager->persist($contract);
        $this->entityManager->flush();
    }
}
