<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\Base\BusinessPayment;
use BusinessCore\Entity\Business;
use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * BusinessPaymentRepository
 */
class BusinessPaymentRepository extends EntityRepository
{
    public function getTotalPaymentsByBusiness(Business $business)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tot', 'tot');

        $sql = 'select COUNT(sub.business_code) as tot from (
                select business_code from business.business_trip_payment
                union all
                select business_code from business.time_package_payment
                union all
                select business_code from business.extra_payment
                union all
                select business_code from business.subscription_payment
                ) sub
                where business_code = :business_code';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('business_code', $business->getCode());

        return $query->getSingleScalarResult();
    }

    public function searchPaymentsByBusiness(Business $business, SearchCriteria $searchCriteria, $count = false)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('business_code', 'business_code');
        $select = 'sub.*';
        if ($count) {
            $select = 'COUNT(sub.*) as tot';
            $rsm->addScalarResult('tot', 'tot');
        } else {
            $rsm->addScalarResult('amount', 'amount');
            $rsm->addScalarResult('currency', 'currency');
            $rsm->addScalarResult('created_ts', 'created_ts');
            $rsm->addScalarResult('payed_on_ts', 'payed_on_ts');
            $rsm->addScalarResult('expected_payed_ts', 'expected_payed_ts');
            $rsm->addScalarResult('id', 'payment_id');
            $rsm->addScalarResult('status', 'status');
            $rsm->addScalarResult('invoice_id', 'invoice_id');
            $rsm->addScalarResult('type', 'type');
        }

        $sql = 'select ' . $select . ' from (
                select business_code, id, created_ts, payed_on_ts, expected_payed_ts, amount, currency, status, invoice_id, \'' . BusinessPayment::TYPE_TRIP . '\' AS type from business.business_trip_payment
                union all
                select business_code, id, created_ts, payed_on_ts, expected_payed_ts, amount, currency, status, invoice_id, \'' . BusinessPayment::TYPE_PACKAGE . '\' AS type from business.time_package_payment
                union all
                select business_code, id, created_ts, payed_on_ts, expected_payed_ts, amount, currency, status, invoice_id, \'' . BusinessPayment::TYPE_EXTRA . '\' AS type from business.extra_payment
                union all
                select business_code, id, created_ts, payed_on_ts, expected_payed_ts, amount, currency, status, invoice_id, \'' . BusinessPayment::TYPE_SUBSCRIPTION . '\' AS type from business.subscription_payment
                ) sub
                where business_code = :business_code ';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('business_code', $business->getCode());

        $searchColumn = $searchCriteria->getSearchColumn();
        $searchValue = $searchCriteria->getSearchValue();
        if (!empty($searchColumn) && !empty($searchValue)) {
            $likeValue = strtolower("%" . $searchValue . "%");
            $sql .= 'AND LOWER(' . $searchColumn . ') LIKE :value ';
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
            $sql .= ' AND ' . $columnFromDate . ' >= :from ';
            $sql .= ' AND ' . $columnToDate . ' <= :to ';
            $query->setParameter('from', $fromDate . ' 00:00:00');
            $query->setParameter('to', $toDate . ' 23:59:59');
        }

        if (!$count) {
            $sortColumn = $searchCriteria->getSortColumn();
            $sortOrder = $searchCriteria->getSortOrder();
            if (!empty($sortColumn) && !empty($sortOrder)) {
                $sql .= 'ORDER BY ' . $sortColumn . ' ' . $sortOrder . ' ';
            }

            $paginationLength = $searchCriteria->getPaginationLength();
            $paginationStart = $searchCriteria->getPaginationStart();
            if (!empty($paginationLength) && !empty($paginationStart)) {
                $sql .= 'LIMIT' . $paginationLength;
                $sql .= ' OFFSET' . $paginationStart;
            }
        }
        $query->setSQL($sql);

        if ($count) {
            return $query->getSingleScalarResult();
        }
        return $query->getResult();
    }

    /**
     * @param string $class
     * @param $id
     * @return BusinessPayment
     */
    public function getPaymentByClassAndId($class, $id)
    {
        $dql = 'SELECT e FROM ' . $class . ' e WHERE e.id = :id';
        $query = $this->getEntityManager()->createQuery();
        $query->setParameter('id', $id);
        $query->setDql($dql);

        return $query->getOneOrNullResult();
    }

    public function getPaymentReportData(Business $business, SearchCriteria $searchCriteria, $sumTotal = false)
    {
        $rsm = new ResultSetMapping();
        if ($sumTotal) {
            $select = 'sum(sub.amount) as total, currency';
            $rsm->addScalarResult('total', 'total');
            $rsm->addScalarResult('currency', 'currency');
        } else {
            $select = 'sub.*';
            $rsm->addScalarResult('amount', 'amount');
            $rsm->addScalarResult('currency', 'currency');
            $rsm->addScalarResult('created_ts', 'created_ts');
            $rsm->addScalarResult('payed_on_ts', 'payed_on_ts');
            $rsm->addScalarResult('id', 'payment_id');
            $rsm->addScalarResult('status', 'status');
            $rsm->addScalarResult('invoice_id', 'invoice_id');
            $rsm->addScalarResult('type', 'type');
        }

        $sql = 'select ' . $select . ' from (
                select business_code, id, created_ts, payed_on_ts, amount, currency, status, invoice_id, \'' . BusinessPayment::TYPE_TRIP . '\' AS type from business.business_trip_payment
                union all
                select business_code, id, created_ts, payed_on_ts, amount, currency, status, invoice_id, \'' . BusinessPayment::TYPE_PACKAGE . '\' AS type from business.time_package_payment
                union all
                select business_code, id, created_ts, payed_on_ts, amount, currency, status, invoice_id, \'' . BusinessPayment::TYPE_EXTRA . '\' AS type from business.extra_payment
                union all
                select business_code, id, created_ts, payed_on_ts, amount, currency, status, invoice_id, \'' . BusinessPayment::TYPE_SUBSCRIPTION . '\' AS type from business.subscription_payment
                ) sub
                where business_code = :business_code ';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('business_code', $business->getCode());

        $searchColumn = $searchCriteria->getSearchColoumn();
        $searchValue = $searchCriteria->getSearchValue();
        if (!empty($searchColumn) && !empty($searchValue)) {
            $likeValue = strtolower("%" . $searchValue . "%");
            $sql .= 'AND LOWER(' . $searchColumn . ') LIKE :value ';
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
            $sql .= ' AND ' . $columnFromDate . ' >= :from ';
            $sql .= ' AND ' . $columnToDate . ' <= :to ';
            $query->setParameter('from', $fromDate . ' 00:00:00');
            $query->setParameter('to', $toDate . ' 23:59:59');
        }

        if (!$sumTotal) {
            $sortColumn = $searchCriteria->getSortColumn();
            $sortOrder = $searchCriteria->getSortOrder();
            if (!empty($sortColumn) && !empty($sortOrder)) {
                $sql .= ' ORDER BY ' . $sortColumn . ' ' . $sortOrder . ' ';
            }
        } else {
            $sql .= ' group by sub.currency';
        }

        $query->setSQL($sql);
        return $query->getResult();
    }
}
