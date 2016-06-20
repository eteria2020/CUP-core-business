<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\SubscriptionPayment;
use BusinessCore\Payments\BusinessPaymentRequest;
use BusinessCore\Service\MockExternalPaymentService as PaymentService;
use Doctrine\ORM\EntityManager;

class SubscriptionService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param PaymentService $paymentService
     */
    public function __construct(
        EntityManager $entityManager,
        PaymentService $paymentService
    ) {
        $this->entityManager = $entityManager;
        $this->paymentService = $paymentService;
    }

    public function paySubscription(SubscriptionPayment $subscriptionPayment)
    {
        $business = $subscriptionPayment->getBusiness();
        if ($business->payWithCreditCard()) {
            $businessPayment = new BusinessPaymentRequest($business, [$subscriptionPayment], true);

            $this->paymentService->pay($businessPayment);
        }
    }
}
