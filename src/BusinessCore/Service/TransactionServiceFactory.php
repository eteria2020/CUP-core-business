<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessTimePackageService = $serviceLocator->get('BusinessCore\Service\BusinessTimePackageService');

        return new TransactionService(
            $entityManager,
            $businessTimePackageService
        );
    }
}
