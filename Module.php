<?php

namespace BusinessCore;

use Zend\EventManager\SharedEventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $sharedEventManager  = $e->getApplication()->getEventManager()->getSharedManager();
        $em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $platform = $em->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('jsonb', 'string');
        $platform->registerDoctrineTypeMapping('employee_status', 'string');
        $platform->registerDoctrineTypeMapping('payment_frequence', 'string');
        $platform->registerDoctrineTypeMapping('payment_type', 'string');

        $this->registerEventListeners($sharedEventManager, $serviceManager);
    }

    /**
     * @param SharedEventManagerInterface $sharedEventManager
     * @param ServiceLocatorInterface $serviceManager
     */
    private function registerEventListeners(
        SharedEventManagerInterface $sharedEventManager,
        ServiceLocatorInterface $serviceManager
    ) {
        $employeeApprovedListener = $serviceManager->get('BusinessCore\Listener\EmployeeApprovedListener');
        $paymentListener = $serviceManager->get('BusinessCore\Listener\PaymentListener');

        $sharedEventManager->attachAggregate($employeeApprovedListener);
        $sharedEventManager->attachAggregate($paymentListener);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
