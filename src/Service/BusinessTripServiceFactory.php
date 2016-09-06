<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessTripServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessPaymentService = $serviceLocator->get('BusinessCore\Service\BusinessPaymentService');
        $businessTripRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessTrip');
        $paymentService = $serviceLocator->get('BusinessCore\Service\PaymentService');

        return new BusinessTripService(
            $entityManager,
            $businessTripRepository,
            $businessPaymentService,
            $paymentService
        );
    }
}
