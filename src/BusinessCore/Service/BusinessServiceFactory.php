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
        $datatableService = $serviceLocator->get('BusinessCore\Service\Datatable');

        $translator = $serviceLocator->get('translator');

        return new BusinessService(
            $entityManager,
            $businessRepository,
            $datatableService,
            $translator
        );
    }
}
