<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ContractServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $contractRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessContract');

        return new ContractService(
            $entityManager,
            $contractRepository
        );
    }
}
