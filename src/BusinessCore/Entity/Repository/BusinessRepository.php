<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityRepository;

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
            'WHERE lower(e.code) LIKE :value OR lower(e.name) LIKE :value'
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
        $count = "SUM((DATE_PART('day', t.timestampBeginning::timestamp - t.timestampEnd::timestamp) * 24 +
    DATE_PART('hour', t.timestampBeginning::timestamp - t.timestampEnd::timestamp)) * 60 +
    DATE_PART('minute', t.timestampBeginning::timestamp - t.timestampEnd::timestamp)) * 60 +
    DATE_PART('second', t.timestampBeginning::timestamp - t.timestampEnd::timestamp) as seconds ";

        $dql = 'SELECT b.name, ' . $count .
                ' FROM \BusinessCore\Entity\BusinessTrip bt
                JOIN bt.business b
                JOIN bt.trip t ';
        $query = $this->getEntityManager()->createQuery();
        if (!empty($from) && !empty($to)) {
            $dql .= 'WHERE t.timestampBeginning >= :from AND t.timestampBeginning <= :to ';
            $query->setParameter('from', $from . ' 00:00:00');
            $query->setParameter('to', $to . ' 23:59:59');
        }

        $dql .=  'GROUP BY b.name';

        $query->setDQL($dql);
        echo "<pre>"; print_r($query->getSQL()); echo "</pre>";die();
        echo "<pre>"; print_r($dql); echo "</pre>";die();

        return $query->getResult();
    }

    public function getBusinessGroupStatsData($businessName, $from, $to)
    {
        $count = "SUM((DATE_PART('day', t.timestampBeginning::timestamp - t.timestampEnd::timestamp) * 24 +
    DATE_PART('hour', t.timestampBeginning::timestamp - t.timestampEnd::timestamp)) * 60 +
    DATE_PART('minute', t.timestampBeginning::timestamp - t.timestampEnd::timestamp)) * 60 +
    DATE_PART('second', t.timestampBeginning::timestamp - t.timestampEnd::timestamp) as seconds ";

        $dql = 'SELECT g.name, ' . $count .
                ' FROM \BusinessCore\Entity\BusinessTrip bt
                JOIN bt.business b
                LEFT JOIN bt.group g
                JOIN bt.trip t
                WHERE b.name = :name ';

        $query = $this->getEntityManager()->createQuery();
        $query->setParameter('name', $businessName);

        if (!empty($from) && !empty($to)) {
            $dql .= 'AND t.timestampBeginning >= :from AND t.timestampBeginning <= :to ';
            $query->setParameter('from', $from . ' 00:00:00');
            $query->setParameter('to', $to . ' 23:59:59');
        }

        $dql .= 'GROUP BY g.name';
        $query->setDQL($dql);

        return $query->getResult();



    }
}
