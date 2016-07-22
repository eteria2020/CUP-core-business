<?php

namespace BusinessCore\Service\Helper;

class SearchCriteria
{
    private $searchColumnNull;
    private $searchColumn;
    private $searchValue;
    private $paginationStart;
    private $paginationLength;
    private $sortColumn;
    private $sortOrder;
    private $fromDate;
    private $toDate;
    private $columnFromDate;
    private $columnToDate;

    /**
     * SearchCriteria constructor.
     *
     * @param $searchColumnNull
     * @param $searchColumn
     * @param $searchValue
     * @param $paginationStart
     * @param $paginationLength
     * @param $sortColumn
     * @param $sortOrder
     * @param $fromDate
     * @param $toDate
     * @param $columnFromDate
     * @param $columnToDate
     */
    public function __construct(
        $searchColumnNull,
        $searchColumn,
        $searchValue,
        $paginationStart,
        $paginationLength,
        $sortColumn,
        $sortOrder,
        $fromDate,
        $toDate,
        $columnFromDate,
        $columnToDate
    ) {
        $this->searchColumn = $searchColumn;
        $this->searchValue = $searchValue;
        $this->paginationStart = $paginationStart;
        $this->paginationLength = $paginationLength;
        $this->sortColumn = $sortColumn;
        $this->sortOrder = $sortOrder;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->columnFromDate = $columnFromDate;
        $this->columnToDate = $columnToDate;
        $this->searchColumnNull = $searchColumnNull;
    }

    /**
     * @return string
     */
    public function getSearchColumn()
    {
        return $this->searchColumn;
    }

    /**
     * @return string
     */
    public function getSearchValue()
    {
        return $this->searchValue;
    }

    /**
     * @return string
     */
    public function getPaginationStart()
    {
        return $this->paginationStart;
    }

    /**
     * @return string
     */
    public function getPaginationLength()
    {
        return $this->paginationLength;
    }

    /**
     * @return string
     */
    public function getSortColumn()
    {
        return $this->sortColumn;
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @return string
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @return string
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @return string
     */
    public function getColumnFromDate()
    {
        return $this->columnFromDate;
    }

    /**
     * @return string
     */
    public function getColumnToDate()
    {
        return $this->columnToDate;
    }

    /**
     * @return mixed
     */
    public function getSearchColumnNull()
    {
        return $this->searchColumnNull;
    }
}
