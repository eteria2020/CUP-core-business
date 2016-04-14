<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\Business;

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

    public function removeEmployee($businessCode, $employeeId)
    {
        $query =  'DELETE FROM \BusinessCore\Entity\BusinessEmployee be
            WHERE be.business = :code AND
            be.employee = :employee ' ;

        $query = $this->getEntityManager()->createQuery($query);
        $query->setParameter('code', $businessCode);
        $query->setParameter('employee', $employeeId);

        return $query->execute();
    }

    public function setEmployeeBlockStatus($businessCode, $employeeId, $block)
    {
        {
            $query =  'UPDATE \BusinessCore\Entity\BusinessEmployee be
                SET be.blocked = :block
                WHERE be.business = :code AND
                be.employee = :employee ' ;

            $query = $this->getEntityManager()->createQuery($query);
            $query->setParameter('block', $block);
            $query->setParameter('code', $businessCode);
            $query->setParameter('employee', $employeeId);

            return $query->execute();
        }
    }
}
