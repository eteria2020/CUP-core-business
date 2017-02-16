<?php

namespace BusinessCore\Service;


use BusinessCore\Service\Helper\SearchCriteria;

class DatatableService
{
    /**
     * @param array $filters
     * @return SearchCriteria
     */
    public function getSearchCriteria(array $filters)
    {
        $searchColumnNull = isset($filters['columnNull']) ? $filters['columnNull'] : null;
        $searchColumn = isset($filters['column']) && $filters['column'] != 'select' ? $filters['column'] : null;
        $searchValue = isset($filters['searchValue']) ? $filters['searchValue'] : null;
        $displayStart = isset($filters['iDisplayStart']) ? $filters['iDisplayStart'] : null;
        $displayLength = isset($filters['iDisplayLength']) ? $filters['iDisplayLength'] : null;
        $sortField = isset($filters['iSortCol_0']) ? 'mDataProp_' . $filters['iSortCol_0'] : '';
        $sortColumn = isset($filters[$sortField]) ? $filters[$sortField] : null;
        $sortOrder = isset($filters['sSortDir_0']) ? $filters['sSortDir_0'] : null;
        $fromDate = isset($filters['fromDate']) ? $filters['fromDate'] : null;
        $toDate = isset($filters['toDate']) ? $filters['toDate'] : null;
        $columnFromDate = isset($filters['columnFromDate']) ? $filters['columnFromDate'] : null;
        $columnToDate = isset($filters['columnToDate']) ? $filters['columnToDate'] : null;

        return new SearchCriteria(
            $searchColumnNull,
            $searchColumn,
            $searchValue,
            $displayStart,
            $displayLength,
            $sortColumn,
            $sortOrder,
            $fromDate,
            $toDate,
            $columnFromDate,
            $columnToDate
        );
    }
}
