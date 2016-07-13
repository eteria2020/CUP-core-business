<?php

namespace BusinessCore\Form\InputData;

use BusinessCore\Entity\BusinessFleet;

class BusinessConfigParams
{
    private $paymentType;
    private $paymentFrequence;
    private $businessMailControl;
    private $subscriptionFeeCents;
    /**
     * @var BusinessFleet
     */
    private $fleet;

    /**
     * @param $paymentType
     * @param $paymentFrequence
     * @param $businessMailControl
     * @param $subscriptionFeeCents
     * @param BusinessFleet $fleet
     */
    public function __construct(
        $paymentType,
        $paymentFrequence,
        $businessMailControl,
        $subscriptionFeeCents,
        BusinessFleet $fleet
    ) {
        $this->paymentType = $paymentType;
        $this->paymentFrequence = $paymentFrequence;
        $this->businessMailControl = $businessMailControl;
        $this->subscriptionFeeCents = $subscriptionFeeCents;
        $this->fleet = $fleet;
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

    /**
     * @return BusinessFleet
     */
    public function getFleet()
    {
        return $this->fleet;
    }
}
