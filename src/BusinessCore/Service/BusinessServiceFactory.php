<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessRepository = $entityManager->getRepository('BusinessCore\Entity\Business');
        $fareRepository = $entityManager->getRepository('BusinessCore\Entity\Fare');
        $businessEmployeeRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessEmployee');
        $translator = $serviceLocator->get('translator');

        return new BusinessService(
            $entityManager,
            $businessRepository,
            $businessEmployeeRepository,
            $fareRepository,
            $translator
        );
    }
}
