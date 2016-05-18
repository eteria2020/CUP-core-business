<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\Employee;
use Doctrine\ORM\EntityRepository;

/**
 * BusinessEmployeeRepository
 */
class BusinessEmployeeRepository extends EntityRepository
{
    public function findActiveAssociation(Employee $employee)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT e FROM \BusinessCore\Entity\BusinessEmployee e '.
            'WHERE e.employee = :employee '.
            'AND e.status != :status'
        );
        $query->setParameter('employee', $employee);
        $query->setParameter('status', BusinessEmployee::STATUS_DELETED);
        return $query->getOneOrNullResult();
    }
}