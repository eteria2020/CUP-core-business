<?php

namespace BusinessCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FareRepository
 */
class FareRepository extends EntityRepository
{
    public function findOne()
    {
        return $this->createQueryBuilder('f')
            ->select()
            ->orderBy('f.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
