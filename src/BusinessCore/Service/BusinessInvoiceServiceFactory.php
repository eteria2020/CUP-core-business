<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessInvoiceServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $businessInvoiceRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessInvoice');

        return new BusinessInvoiceService(
            $entityManager,
            $businessInvoiceRepository
        );
    }
}
