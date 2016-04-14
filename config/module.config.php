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
    ],
    'service_manager' => [
        'factories' => [
            'BusinessCore\Service\BusinessService' => 'BusinessCore\Service\BusinessServiceFactory',
            'BusinessCore\Service\DatatableService' => 'BusinessCore\Service\DatatableServiceFactory'
        ]
    ],
];
