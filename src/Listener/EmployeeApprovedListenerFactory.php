<?php

namespace BusinessCore\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmployeeApprovedListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $emailService = $serviceLocator->get('BusinessCore\Service\BusinessEmailService');
        $translator = $serviceLocator->get('Translator');
        $businessEmployeeService = $serviceLocator->get('BusinessCore\Service\BusinessEmployeeService');
        return new EmployeeApprovedListener($emailService, $translator, $businessEmployeeService);
    }
}
