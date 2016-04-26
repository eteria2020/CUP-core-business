<?php

namespace BusinessCore;

return [
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity']
            ],
            'orm_default'             => [
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ],
        ],
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'module/BusinessCore/doctrine-migrations',
                'name'      => 'Doctrine Database Migrations',
                'namespace' => 'DoctrineORMModule\Migrations',
                'table'     => 'business.migrations',
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            'BusinessCore\Service\BusinessService' => 'BusinessCore\Service\BusinessServiceFactory',
            'BusinessCore\Service\DatatableService' => 'BusinessCore\Service\DatatableServiceFactory',
            'BusinessCore\Service\GroupService' => 'BusinessCore\Service\GroupServiceFactory'
        ]
    ],
    'view_helpers'    => [
        'factories' => [
            'businessEmployeeStatus' => 'BusinessCore\View\Helper\BusinessEmployeeStatusHelperFactory',
        ]
    ],
];
