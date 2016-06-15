<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PdfServicefactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $viewRenderer = $serviceLocator->get('view_manager')->getRenderer();
        $pdfService = $serviceLocator->get('mvlabssnappy.pdf.service');

        return new PdfService($viewRenderer, $pdfService);
    }
}
