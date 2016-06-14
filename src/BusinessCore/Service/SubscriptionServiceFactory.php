<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SubscriptionServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        //TODO (MOCK)
        $externalPaymentService = $serviceLocator->get('MockExternalPaymentService');

        return new SubscriptionService(
            $entityManager,
            $externalPaymentService
        );
    }
}
