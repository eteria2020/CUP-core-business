<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessFleetServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessFleetRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessFleet');

        return new BusinessFleetService(
            $entityManager,
            $businessFleetRepository
        );
    }
}
