<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Repository\BusinessFleetRepository;
use Doctrine\ORM\EntityManager;

class BusinessFleetService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var BusinessFleetRepository
     */
    private $businessFleetRepository;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param BusinessFleetRepository $businessFleetRepository
     */
    public function __construct(
        EntityManager $entityManager,
        BusinessFleetRepository $businessFleetRepository
    ) {
        $this->entityManager = $entityManager;
        $this->businessFleetRepository = $businessFleetRepository;
    }

    public function findFleetById($id)
    {
        return $this->businessFleetRepository->findOneBy(['id' => $id]);
    }

    public function findAll()
    {
        return $this->businessFleetRepository->findAll();
    }

    public function getFleetByCode($filterFleet)
    {
        return $this->businessFleetRepository->findOneBy(['code' => $filterFleet]);
    }
}
