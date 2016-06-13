<?php

namespace BusinessCore\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PaymentListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $transactionService = $serviceLocator->get('BusinessCore\Service\TransactionService');
        $translator = $serviceLocator->get('Translator');
        return new PaymentListener($transactionService);
    }
}
