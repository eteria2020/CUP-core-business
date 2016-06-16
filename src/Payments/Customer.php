<?php

namespace Payments;

class Customer
{
    /**
     * @param Contract
     */
    private $contract;

    /**
     * @return bool
     */
    public function hasContract()
    {
        return false;
    }
}