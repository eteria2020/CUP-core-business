<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessFleet;
use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Zend\Form\Element\DateTime;

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

    public function searchInvoicesByBusiness(Business $business, SearchCriteria $searchCriteria, $count = false)
    {
        $select = 'bi';
        if ($count) {
            $select = 'COUNT(bi.id)';
        }

        $dql = 'SELECT ' . $select .
                ' FROM \BusinessCore\Entity\BusinessInvoice bi
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

        $query->setDQL($dql);

        if ($count) {
            return $query->getSingleScalarResult();
        }
        return $query->getResult();
    }

    public function getNewInvoiceNumber(BusinessFleet $fleet)
    {
        $resultSetMapping = new ResultSetMapping();
        $resultSetMapping->addScalarResult('number', 'number');
        $newInvoiceNumberQuery = $this->getEntityManager()->createNativeQuery(
            'UPDATE business.business_invoice_number SET number = number + 1 '.
            'WHERE year = :year '.
            'AND fleet_id = :fleet_id '.
            'RETURNING number;',
            $resultSetMapping
        );

        $invoiceYear = date_create()->format('Y');
        $fleetId = $fleet->getId();
        $newInvoiceNumberQuery->setParameter('year', $invoiceYear);
        $newInvoiceNumberQuery->setParameter('fleet_id', $fleetId);

        $newInvoiceNumber = $newInvoiceNumberQuery->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        if (is_null($newInvoiceNumber)) {
            // if no invoice was generated before for the same year and fleet
            // we insert a new line starting from 1
            $newInvoiceNumberQuery = $this->getEntityManager()->createNativeQuery(
                'INSERT INTO business.business_invoice_number (year, fleet_id, number) VALUES '.
                '(:year, :fleet_id, 1) '.
                'RETURNING number;',
                $resultSetMapping
            );
            $newInvoiceNumberQuery->setParameter('year', $invoiceYear);
            $newInvoiceNumberQuery->setParameter('fleet_id', $fleetId);
            $newInvoiceNumber =  $newInvoiceNumberQuery->getSingleScalarResult();
        }

        return $newInvoiceNumber;
    }
}
