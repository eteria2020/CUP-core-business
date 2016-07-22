<?php

namespace BusinessCore\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PaymentListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $transactionService = $serviceLocator->get('BusinessCore\Service\TransactionService');
        $contractService = $serviceLocator->get('BusinessCore\Service\ContractService');
        $businessService = $serviceLocator->get('BusinessCore\Service\BusinessService');

        return new PaymentListener($transactionService, $contractService, $businessService);
    }
}
