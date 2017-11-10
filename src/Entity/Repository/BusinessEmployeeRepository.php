<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\Employee;
use Doctrine\ORM\EntityRepository;

/**
 * BusinessEmployeeRepository
 */
class BusinessEmployeeRepository extends EntityRepository {

    public function findActiveAssociation(Employee $employee) {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
                'SELECT e FROM \BusinessCore\Entity\BusinessEmployee e ' .
                'WHERE e.employee = :employee ' .
                'AND e.status != :status'
        );
        $query->setParameter('employee', $employee);
        $query->setParameter('status', BusinessEmployee::STATUS_DELETED);
        return $query->getOneOrNullResult();
    }

    /**
     * Remove Employee and clead the pin field in the Customers table
     * @param Employee $employee
     * @return type
     */
    public function removeEmployeeAndClaenPinCompany(Employee $employee) {

        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'DELETE \BusinessCore\Entity\BusinessEmployee e ' .
                'WHERE e.employee = :employee '
        );

        $query->setParameter('employee', $employee);
        $query->execute();

        $query = $em->createQuery(
                'UPDATE \BusinessCore\Entity\Employee e SET e.pin = :pin WHERE e.id = :id'
        );

        $pin = json_decode($employee->getPin(), true);
        unset($pin['company']);
        unset($pin['companyPinDisabled']);

        $query->setParameter('pin', json_encode($pin));
        $query->setParameter('id', $employee->getId());

        return $query->execute();
    }

}
