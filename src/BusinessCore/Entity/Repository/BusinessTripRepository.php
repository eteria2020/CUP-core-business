<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\Business;
use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityRepository;

/**
 * BusinessTripRepository
 */
class BusinessTripRepository extends EntityRepository
{
    public function countAllByBusiness(Business $business)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT COUNT(bt.trip) FROM \BusinessCore\Entity\BusinessTrip bt WHERE bt.business = :business');
        $query->setParameter('business', $business);
        return $query->getSingleScalarResult();
    }

    public function searchTripsByBusiness(Business $business, SearchCriteria $searchCriteria, $count = false)
    {

        $select = 'bt';
        if ($count) {
            $select = 'COUNT(bt.trip)';
        }
        $dql = 'SELECT ' . $select .
                ' FROM \BusinessCore\Entity\BusinessTrip bt
                LEFT JOIN bt.group g
                JOIN bt.trip t
                JOIN t.employee e
                WHERE bt.business = :business ';

        $query = $this->getEntityManager()->createQuery();
        $query->setParameter('business', $business);

        $searchColumn = $searchCriteria->getSearchColoumn();
        $searchValue = $searchCriteria->getSearchValue();
        if (!empty($searchColumn) && !empty($searchValue)) {
            $likeValue = strtolower("%" . $searchValue . "%");
            $dql .= 'AND LOWER(' . $searchColumn . ') LIKE :value ';
            $query->setParameter('value', $likeValue);
        }
        $fromDate = $searchCriteria->getFromDate();
        $toDate = $searchCriteria->getToDate();
        $columnFromDate = $searchCriteria->getColumnFromDate();
        $columnToDate = $searchCriteria->getColumnToDate();
        if (!empty($fromDate) &&
            !empty($toDate) &&
            !empty($columnFromDate) &&
            !empty($columnToDate)
        ) {
            $dql .= ' AND ' . $columnFromDate . ' >= :from ';
            $dql .= ' AND ' . $columnToDate . ' <= :to ';
            $query->setParameter('from', $fromDate . ' 00:00:00');
            $query->setParameter('to', $toDate . ' 23:59:59');
        }

        if (!$count) {
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
        }

        $query->setDql($dql);

        if ($count) {
            return $query->getSingleScalarResult();
        }
        return $query->getResult();
    }
}
