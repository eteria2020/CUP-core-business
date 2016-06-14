<?php

namespace BusinessCore\Form\InputData;

class BusinessConfigParams
{
    private $paymentType;
    private $paymentFrequence;
    private $businessMailControl;
    private $subscriptionFeeCents;

    /**
     * @param $paymentType
     * @param $paymentFrequence
     * @param $businessMailControl
     * @param $subscriptionFeeCents
     */
    public function __construct(
        $paymentType,
        $paymentFrequence,
        $businessMailControl,
        $subscriptionFeeCents
    ) {
        $this->paymentType = $paymentType;
        $this->paymentFrequence = $paymentFrequence;
        $this->businessMailControl = $businessMailControl;
        $this->subscriptionFeeCents = $subscriptionFeeCents;
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

    /**
     * @return mixed
     */
    public function getSubscriptionFeeCents()
    {
        return $this->subscriptionFeeCents;
    }
}
