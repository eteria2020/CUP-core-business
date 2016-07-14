<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\BusinessContract;
use Doctrine\ORM\EntityManager;

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

    public function createContract(BusinessContract $contract)
    {
        $this->entityManager->persist($contract);
        $this->entityManager->flush();
    }
}
