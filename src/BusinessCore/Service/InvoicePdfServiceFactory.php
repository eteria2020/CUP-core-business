<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InvoicePdfServicefactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $viewRenderer = $serviceLocator->get('view_manager')->getRenderer();
        $pdfService = $serviceLocator->get('mvlabssnappy.pdf.service');

        return new InvoicePdfService($viewRenderer, $pdfService);
    }
}
