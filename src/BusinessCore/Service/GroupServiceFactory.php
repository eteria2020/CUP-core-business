<?php

namespace BusinessCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GroupServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $groupRepository = $entityManager->getRepository('BusinessCore\Entity\Group');
        $businessRepository = $entityManager->getRepository('BusinessCore\Entity\Business');
        $businessEmployeeRepository = $entityManager->getRepository('BusinessCore\Entity\BusinessEmployee');
        $translator = $serviceLocator->get('translator');

        return new GroupService(
            $entityManager,
            $groupRepository,
            $businessRepository,
            $businessEmployeeRepository,
            $translator
        );
    }
}
