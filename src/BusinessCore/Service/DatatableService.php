<?php

namespace BusinessCore\Service;

use Doctrine\ORM\EntityManager;

class DatatableService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * Builds a query based on the entity and parameters passed and returns the
     * results. If $count is set to true, returns the COUNT() of the results.
     *
     * @param string $entity
     * @param array $options
     * @param boolean $count
     * @return mixed[] | integer
     */
    public function getData($entity, array $options, $count = false)
    {
        $as_parameters = [];
        $where = false;
        $dql = 'SELECT ' . ($count ? 'COUNT(e)' : ('e')) . ' FROM \BusinessCore\Entity\\' . $entity . ' e ';

        $query = $this->entityManager->createQuery();

        if ($options['column'] != 'select' &&
            !empty($options['searchValue']) &&
            !empty($options['column'])
        ) {
            // if there is a selected filter, we apply it to the query
            $checkIdColumn = strpos($options['column'], '.id');

            if ($options['column'] == 'id' || $checkIdColumn) {
                $withAndWhere = $where ? 'AND ' : 'WHERE ';
                $dql .= $withAndWhere . $options['column'] . ' = :id ';
                $as_parameters['id'] = (int)$options['searchValue'];
            } else {
                $value = strtolower("%" . $options['searchValue'] . "%");
                $withAndWhere = $where ? 'AND ' : 'WHERE ';
                $dql .= $withAndWhere . ' LOWER(' . $options['column'] . ') LIKE :value ';
                $as_parameters['value'] = $value;
            }
            $where = true;
        }

        // query a fixed parameter
        if (!empty($options['fixedColumn']) &&
           !empty($options['fixedValue']) &&
           !empty($options['fixedLike'])
        ) {
            $withAndWhere = $where ? 'AND ' : 'WHERE ';
            $dql .= $withAndWhere . $options['fixedColumn'] . ' ';
            if ($options['fixedValue'] != null) {
                $dql .= ($options['fixedLike'] == 'true' ? 'LIKE ' : '= ') .
                ':fixedValue ';
                $as_parameters['fixedValue'] = $options['fixedValue'];
            } else {
                $dql .= 'IS NULL ';
            }
            $where = true;
        }

        //query with null
        if ($options['column'] != 'select' &&
            isset($options['columnNull']) &&
            !empty($options['columnNull'])
        ) {
            $withAndWhere = $where ? 'AND ' : 'WHERE ';
            $dql .= $withAndWhere . $options['columnNull'] . " IS NULL ";
        }

        if (count($as_parameters) > 0) {
            $query->setParameters($as_parameters);
        }

        // cannot set order if using count.
        // might order by not selected field causing failure
        if (!$count) {
            // apply the requested ordering
            $orderFieldId = $options['iSortCol_0'];
            $orderField = $options['mDataProp_' . $orderFieldId];
            $dql .= 'ORDER BY ' . $orderField . ' ' . $options['sSortDir_0'] . ' ';
        }

        // limit and offset for pagination
        if ($options['withLimit']) {
            $query->setMaxResults($options['iDisplayLength']);
            $query->setFirstResult($options['iDisplayStart']);
        }

        $query->setDql($dql);

        if ($count) {
            return $query->getSingleScalarResult();
        }
        return $query->getResult();
    }
}
