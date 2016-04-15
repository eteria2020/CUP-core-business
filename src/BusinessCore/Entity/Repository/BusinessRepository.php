<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Service\Helper\SearchCriteria;

/**
 * BusinessRepository
 */
class BusinessRepository extends \Doctrine\ORM\EntityRepository
{
    public function countAll()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT COUNT(e.code) FROM \BusinessCore\Entity\Business e');
        return $query->getSingleScalarResult();
    }

    /**
     * @param $code
     * @return Business
     */
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

    public function setEmployeeStatus($businessCode, $employeeId, $status)
    {
        $query =  'UPDATE \BusinessCore\Entity\BusinessEmployee be
            SET be.status = :status,  be.confirmedTs = :now
            WHERE be.business = :code AND
            be.employee = :employee ' ;

        $query = $this->getEntityManager()->createQuery($query);
        $query->setParameter('status', $status);
        $query->setParameter('now', date_create());
        $query->setParameter('code', $businessCode);
        $query->setParameter('employee', $employeeId);

        return $query->execute();
    }

    public function searchBusinesses(SearchCriteria $searchCriteria)
    {
        $dql = 'SELECT e FROM \BusinessCore\Entity\Business e ';

        $query = $this->getEntityManager()->createQuery();

        $searchColumn = $searchCriteria->getSearchColoumn();
        $searchValue = $searchCriteria->getSearchValue();
        if (!empty($searchColumn) && !empty($searchValue)) {
            $likeValue = strtolower("%" . $searchValue . "%");
            $dql .= 'WHERE LOWER(' . $searchColumn . ') LIKE :value ';
            $query->setParameter('value', $likeValue);
        }
        $sortColumn = $searchCriteria->getSortColumn();
        $sortOrder = $searchCriteria->getSortOrder();
        if (!empty($sortColumn) && !empty($sortOrder)) {
            $dql .= 'ORDER BY ' . $sortColumn . ' ' . $sortOrder . ' ';
        }

        $paginationLength = $searchCriteria->getPaginationLength();
        $paginationStart = $searchCriteria->getPaginationStart();
        if (!empty($paginationLength) && !empty($paginationStart)) {
            $query->setMaxResults($paginationLength);
            $query->setFirstResult($paginationStart);
        }

        $query->setDql($dql);

        return $query->getResult();
    }
}
