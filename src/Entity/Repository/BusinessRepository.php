<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\Business;
use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * BusinessRepository
 */
class BusinessRepository extends EntityRepository
{
    public function findBySearchValue($value)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT e FROM \BusinessCore\Entity\Business e '.
            'WHERE lower(e.name) LIKE :value'
        );
        $likeValue = strtolower("%" . $value . "%");
        $query->setParameter('value', $likeValue);
        return $query->getResult();
    }

    public function countAll()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT COUNT(e.code) FROM \BusinessCore\Entity\Business e');
        return $query->getSingleScalarResult();
    }

    public function searchBusinesses(SearchCriteria $searchCriteria)
    {
        $dql = 'SELECT e FROM \BusinessCore\Entity\Business e ';

        $query = $this->getEntityManager()->createQuery();

        $searchColumn = $searchCriteria->getSearchColumn();
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

    public function getBusinessStatsData($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('name', 'business_name');
        $rsm->addScalarResult('minutes', 'minutes');

        $sql = 'SELECT b.name, SUM(EXTRACT(EPOCH FROM(t.timestamp_end - t.timestamp_beginning))) / 60 as minutes
                FROM business.trip as t
                JOIN business.business_trip AS bt ON (bt.trip_id = t.id)
                JOIN business.business AS b ON (b.code = bt.business_code)';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        if (!empty($from) && !empty($to)) {
            $sql .= ' WHERE t.timestamp_beginning >= :from AND t.timestamp_end <= :to ';
            $query->setParameter('from', $from . ' 00:00:00');
            $query->setParameter('to', $to . ' 23:59:59');
        }

        $sql .= 'GROUP BY b.name';
        $query->setSQL($sql);

        return $query->getResult();
    }

    public function getBusinessGroupStatsData($businessName, $from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('name', 'group_name');
        $rsm->addScalarResult('minutes', 'minutes');

        $sql = 'SELECT g.name, SUM(EXTRACT(EPOCH FROM(t.timestamp_end - t.timestamp_beginning))) / 60 as minutes
                FROM business.business_trip as bt
                JOIN business.business AS b ON (b.code = bt.business_code)
                LEFT JOIN business.employee_group as g ON (bt.group_id = g.id)
                JOIN business.trip AS t ON (bt.trip_id = t.id)
                WHERE b.name = :name ';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('name', $businessName);

        if (!empty($from) && !empty($to)) {
            $sql .= 'AND t.timestamp_beginning >= :from AND t.timestamp_beginning <= :to ';
            $query->setParameter('from', $from . ' 00:00:00');
            $query->setParameter('to', $to . ' 23:59:59');
        }

        $sql .= 'GROUP BY g.name';
        $query->setSQL($sql);

        return $query->getResult();

    }

    public function findBusinessWebuser(Business $business)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT e FROM \BusinessCore\Entity\Webuser e '.
            'WHERE e.business = :business'
        );
        $query->setParameter('business', $business);
        return $query->getOneOrNullResult();
    }
}
