<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessTripServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessTripRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessTrip');

        return new BusinessTripService(
            $entityManager,
            $businessTripRepository
        );
    }
}
