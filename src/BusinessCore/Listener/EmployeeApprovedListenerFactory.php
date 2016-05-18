<?php

namespace BusinessCore\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmployeeApprovedListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $emailService = $serviceLocator->get('BusinessCore\Service\BusinessEmailService');
        return new EmployeeApprovedListener($emailService);
    }
}
