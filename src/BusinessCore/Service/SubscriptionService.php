<?php

namespace BusinessCore\Service;


use BusinessCore\Entity\Business;
use BusinessCore\Entity\SubscriptionPayment;
use BusinessCore\Payments\BusinessPaymentRequest;
use Doctrine\ORM\EntityManager;

class SubscriptionService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var MockExternalPaymentService
     */
    private $mockExternalPaymentService;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param MockExternalPaymentService $mockExternalPaymentService
     */
    public function __construct(
        EntityManager $entityManager,
        MockExternalPaymentService $mockExternalPaymentService
    ) {
        $this->entityManager = $entityManager;
        $this->mockExternalPaymentService = $mockExternalPaymentService;
    }

    public function paySubscription(Business $business)
    {
        $subscriptionPayment = new SubscriptionPayment($business, $business->getSubscriptionFeeCents(), 'EUR');

        $this->entityManager->persist($subscriptionPayment);
        $this->entityManager->flush();

        if ($business->getPaymentType() == Business::TYPE_CREDIT_CARD) {
            $businessPayment = new BusinessPaymentRequest($business, [$subscriptionPayment], true);

            $this->mockExternalPaymentService->pay($businessPayment);
        }
    }
}
