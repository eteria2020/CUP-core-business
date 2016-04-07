<?php

namespace BusinessCore\Entity\Repository;

/**
 * BusinessRepository
 */
class BusinessRepository extends \Doctrine\ORM\EntityRepository
{
    public function getBusinessByCode($code)
    {
        $query =  'SELECT e
            FROM \BusinessCore\Entity\Business e
            WHERE lower(e.code) = lower(:code)';

        $query = $this->getEntityManager()->createQuery($query);
        $query->setParameter('code', $code);

        return $query->getOneOrNullResult();
    }
}
