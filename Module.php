<?php

namespace BusinessCore;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $platform = $em->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('jsonb', 'string');
        $platform->registerDoctrineTypeMapping('employee_status', 'string');
        $platform->registerDoctrineTypeMapping('payment_frequence', 'string');
        $platform->registerDoctrineTypeMapping('payment_type', 'string');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
}
