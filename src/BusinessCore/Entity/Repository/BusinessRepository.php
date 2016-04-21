<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\BusinessEmployee;
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
     * @param $businessCode
     * @param $employeeId
     * @return BusinessEmployee
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBusinessEmployeeAssociation($businessCode, $employeeId)
    {
        $query =  'SELECT be FROM \BusinessCore\Entity\BusinessEmployee be
            WHERE be.business = :code AND
            be.employee = :employee ';

        $query = $this->getEntityManager()->createQuery($query);
        $query->setParameter('code', $businessCode);
        $query->setParameter('employee', $employeeId);
        return $query->getOneOrNullResult();
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
