<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessTimePackageServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $timePackageRepository = $entityManager->getRepository('BusinessCore\Entity\TimePackage');

        $translator = $serviceLocator->get('translator');

        return new BusinessTimePackageService(
            $entityManager,
            $timePackageRepository,
            $translator
        );
    }
}
