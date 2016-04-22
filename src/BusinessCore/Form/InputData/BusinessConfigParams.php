<?php

namespace BusinessCore\Form\InputData;

class BusinessConfigParams
{
    private $paymentType;
    private $paymentFrequence;
    private $businessMailControl;

    /**
     * @param $paymentType
     * @param $paymentFrequence
     * @param $businessMailControl
     */
    public function __construct(
        $paymentType,
        $paymentFrequence,
        $businessMailControl
    ) {
        $this->paymentType = $paymentType;
        $this->paymentFrequence = $paymentFrequence;
        $this->businessMailControl = $businessMailControl;
    }

    /**
     * @return mixed
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @return mixed
     */
    public function getPaymentFrequence()
    {
        return $this->paymentFrequence;
    }

    /**
     * @return mixed
     */
    public function getBusinessMailControl()
    {
        return $this->businessMailControl;
    }
}
