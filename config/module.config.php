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
            'BusinessCore\Service\PdfService' => 'BusinessCore\Service\PdfServiceFactory',
            'BusinessCore\Service\BusinessTimePackageService' => 'BusinessCore\Service\BusinessTimePackageServiceFactory',
            'BusinessCore\Service\BusinessPaymentService' => 'BusinessCore\Service\BusinessPaymentServiceFactory',
            'BusinessCore\Service\BusinessEmailService' => 'BusinessCore\Service\BusinessEmailServiceFactory',
            'BusinessCore\Service\TransactionService' => 'BusinessCore\Service\TransactionServiceFactory',
            'BusinessCore\Service\SubscriptionService' => 'BusinessCore\Service\SubscriptionServiceFactory',
            'BusinessCore\Service\ContractService' => 'BusinessCore\Service\ContractServiceFactory',
            'BusinessCore\Listener\EmployeeApprovedListener' => 'BusinessCore\Listener\EmployeeApprovedListenerFactory',
            'BusinessCore\Listener\PaymentListener' => 'BusinessCore\Listener\PaymentListenerFactory'
        ],
        //TODO (MOCK)
        'invokables' => [
            'PaymentService' => 'BusinessCore\Service\MockExternalPaymentService'
        ],
    ],
    'view_helpers'    => [
        'factories' => [
            'businessEmployeeStatus' => 'BusinessCore\View\Helper\BusinessEmployeeStatusHelperFactory',
        ],
        'invokables' => [
            'groupLink' => 'BusinessCore\View\Helper\GroupLinkHelper',
            'businessEmployeeAvailableActionButtons' =>
                'BusinessCore\View\Helper\BusinessEmployeeAvailableActionButtonHelper',
        ]
    ],
    'mvlabs-snappy' => [
        'pdf' => [
            'binary'  => __DIR__ . '/../../../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64',
            'options' => [],
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __DIR__ . '/../public/assets-modules/business-core',
            ]
        ]
    ]
];
