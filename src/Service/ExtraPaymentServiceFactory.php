<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExtraPaymentServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $paymentService = $serviceLocator->get('BusinessCore\Service\PaymentService');
        $transactionService = $serviceLocator->get('BusinessCore\Service\TransactionService');

        return new ExtraPaymentService(
            $entityManager,
            $paymentService,
            $transactionService
        );
    }
}
