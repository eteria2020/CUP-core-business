<?php

namespace BusinessCore\Service\Helper;

class SearchCriteria
{
    private $searchColoumn;
    private $searchValue;
    private $paginationStart;
    private $paginationLength;
    private $sortColumn;
    private $sortOrder;

    /**
     * SearchCriteria constructor.
     * @param $searchColoumn
     * @param $searchValue
     * @param $paginationStart
     * @param $paginationLength
     * @param $sortColumn
     * @param $sortOrder
     */
    public function __construct(
        $searchColoumn,
        $searchValue,
        $paginationStart,
        $paginationLength,
        $sortColumn,
        $sortOrder
    ) {
        $this->searchColoumn = $searchColoumn;
        $this->searchValue = $searchValue;
        $this->paginationStart = $paginationStart;
        $this->paginationLength = $paginationLength;
        $this->sortColumn = $sortColumn;
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return mixed
     */
    public function getSearchColoumn()
    {
        return $this->searchColoumn;
    }

    /**
     * @return mixed
     */
    public function getSearchValue()
    {
        return $this->searchValue;
    }

    /**
     * @return mixed
     */
    public function getPaginationStart()
    {
        return $this->paginationStart;
    }

    /**
     * @return mixed
     */
    public function getPaginationLength()
    {
        return $this->paginationLength;
    }

    /**
     * @return mixed
     */
    public function getSortColumn()
    {
        return $this->sortColumn;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
}
