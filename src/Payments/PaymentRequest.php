<?php

namespace Payments;

use Payments\Customer;
use Payments\Value\Amount;

interface PaymentRequest
{
    /**
     * @return Customer
     */
    public function customer();

    /**
     * @return Amount
     */
    public function amount();

    /**
     * @return bool
     */
    public function isFirstPayment();
}