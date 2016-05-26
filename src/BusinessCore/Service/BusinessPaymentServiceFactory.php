<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessPaymentServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessPaymentRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessPayment');

        return new BusinessPaymentService(
            $entityManager,
            $businessPaymentRepository
        );
    }
}
