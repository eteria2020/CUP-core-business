<?php

namespace BusinessCore\Service;

use Zend\EventManager\EventManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessEmployeeServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessRepository = $entityManager->getRepository('BusinessCore\Entity\Business');
        $businessEmailService = $serviceLocator->get('BusinessCore\Service\BusinessEmailService');
        $businessEmployeeRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessEmployee');
        $employeeRepository = $entityManager->getRepository('BusinessCore\Entity\Employee');
        $translator = $serviceLocator->get('translator');
        $eventManager = new EventManager('BusinessService');

        return new BusinessEmployeeService(
            $entityManager,
            $businessRepository,
            $businessEmployeeRepository,
            $employeeRepository,
            $translator,
            $eventManager,
            $businessEmailService
        );
    }
}
