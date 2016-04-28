<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessTripServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessRepository = $entityManager->getRepository('BusinessCore\Entity\Business');
        $businessEmployeeRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessEmployee');
        $businessTripRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessTrip');
        $translator = $serviceLocator->get('translator');

        return new BusinessTripService(
            $entityManager,
            $businessRepository,
            $businessEmployeeRepository,
            $businessTripRepository,
            $translator
        );
    }
}
