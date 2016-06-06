<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\Business;
use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityRepository;

/**
 * BusinessPaymentRepository
 */
class BusinessPaymentRepository extends EntityRepository
{
    public function getTotalPaymentsByBusiness($business)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT COUNT(bp.id) FROM \BusinessCore\Entity\BusinessPayment bp WHERE bp.business = :business');
        $query->setParameter('business', $business);
        return $query->getSingleScalarResult();
    }

    public function searchPaymentsByBusiness(Business $business, SearchCriteria $searchCriteria, $count = false)
    {
        $select = 'bp';
        if ($count) {
            $select = 'COUNT(bp.id)';
        }
        $dql = 'SELECT ' . $select .
                ' FROM \BusinessCore\Entity\BusinessPayment bp
                WHERE bp.business = :business ';

        $query = $this->getEntityManager()->createQuery();
        $query->setParameter('business', $business);

        $searchColumn = $searchCriteria->getSearchColoumn();
        $searchValue = $searchCriteria->getSearchValue();
        if (!empty($searchColumn) && !empty($searchValue)) {
            $likeValue = strtolower("%" . $searchValue . "%");
            $dql .= 'AND LOWER(' . $searchColumn . ') LIKE :value ';
            $query->setParameter('value', $likeValue);
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
