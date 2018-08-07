<?php

namespace BusinessCore\Service;

use Zend\Mail\Transport\Factory as MailTransportFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BusinessEmailServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $config = $serviceLocator->get('Configuration');
        $transportConfig = $config['emailTransport']; // uses the same config as SharengoCore local.php
        $emailSettings = $config['emailSettings'];
        $mailsRepository = $entityManager->getRepository('\BusinessCore\Entity\Mails');

        $emailTransport = MailTransportFactory::create($transportConfig);

        return new BusinessEmailService($emailTransport, $emailSettings, $mailsRepository);
    }
}
