<?php

namespace BusinessCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BusinessContractRepository
 */
class BusinessContractRepository extends EntityRepository {

    public function findBusinessContractById($contractId) {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
                'SELECT c FROM \BusinessCore\Entity\BusinessContract c ' .
                'WHERE c.id = :id '
        );

        $query->setParameter('id', $contractId);
        return $query->getOneOrNullResult();
    }

}
