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
            'BusinessCore\Service\GroupService' => 'BusinessCore\Service\GroupServiceFactory',
            'BusinessCore\Service\BusinessTripService' => 'BusinessCore\Service\BusinessTripServiceFactory',
            'BusinessCore\Service\BusinessInvoiceService' => 'BusinessCore\Service\BusinessInvoiceServiceFactory',
            'BusinessCore\Service\InvoicePdfService' => 'BusinessCore\Service\InvoicePdfServiceFactory',
        ]
    ],
    'view_helpers'    => [
        'factories' => [
            'businessEmployeeStatus' => 'BusinessCore\View\Helper\BusinessEmployeeStatusHelperFactory',
        ]
    ],
    'mvlabs-snappy' => [
        'pdf' => [
            'binary'  => __DIR__ . '/../../../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64',
            'options' => [],
        ]
    ],
    'view_manager' => [
        'template_map' => [
            'layout/pdf-layout' => __DIR__ . '/../view/pdf/layout_pdf.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __DIR__ . '/../public',
            ]
        ]
    ],
    'invoice' => [
        'template_version' => '4',
        'subscription_amount' => 1000,
        'iva_percentage' => 22
    ],
];
