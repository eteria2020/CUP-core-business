<?php

namespace BusinessCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use \BusinessCore\Entity\BusinessContract;

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

    /**
     * Disable all old business contracts except the current
     * @param BusinessContract $contract
     * @return type
     */
    public function disableOldContractExceptCurrent(BusinessContract $contract){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
                'UPDATE \BusinessCore\Entity\BusinessContract c '.
                'SET c.disabledDate = :now '.
                'WHERE c.business = :business AND '.
                'c.disabledDate IS NULL AND '.
                'c.id <> :contract_id');

        $query->setParameter('now',  date_create());
        $query->setParameter('business', $contract->getBusiness());
        $query->setParameter('contract_id', $contract->getId());
        return $query->execute();
    }

}
