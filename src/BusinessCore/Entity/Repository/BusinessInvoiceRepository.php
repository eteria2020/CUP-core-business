<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\Business;
use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityRepository;

/**
 * BusinessInvoiceRepository
 */
class BusinessInvoiceRepository extends EntityRepository
{
    public function countAllByBusiness(Business $business)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT COUNT(bi.id) FROM \BusinessCore\Entity\BusinessInvoice bi WHERE bi.business = :business');
        $query->setParameter('business', $business);
        return $query->getSingleScalarResult();
    }

    public function searchInvoicesByBusiness(Business $business, SearchCriteria $searchCriteria)
    {
        $dql = 'SELECT bi
                FROM \BusinessCore\Entity\BusinessInvoice bi
                WHERE bi.business = :business ';

        $query = $this->getEntityManager()->createQuery();
        $query->setParameter('business', $business);

        $searchColumn = $searchCriteria->getSearchColumn();
        $searchValue = $searchCriteria->getSearchValue();
        if (!empty($searchColumn) && !empty($searchValue)) {
            $likeValue = strtolower("%" . $searchValue . "%");
            $dql .= 'AND LOWER(' . $searchColumn . ') LIKE :value ';
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
