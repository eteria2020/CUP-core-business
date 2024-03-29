<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessPaymentServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        //all payments have the same repository, so I get one from a random payment entity
        $businessPaymentRepository = $entityManager->getRepository('BusinessCore\Entity\TimePackagePayment');
        $businessTimePackageService = $serviceLocator->get('BusinessCore\Service\BusinessTimePackageService');
        $businessService = $serviceLocator->get('BusinessCore\Service\BusinessService');

        $translator = $serviceLocator->get('translator');

        return new BusinessPaymentService(
            $entityManager,
            $businessPaymentRepository,
            $businessTimePackageService,
            $businessService,
            $translator
        );
    }
}
