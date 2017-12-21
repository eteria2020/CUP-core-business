<?php

namespace BusinessCore;

return [
    'business-invoice' => [
        'template_version' => '1',
        'vat_percentage' => 22
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
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
                'directory' => __DIR__ . '/../doctrine-migrations',
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
            'BusinessCore\Service\BusinessFleetService' => 'BusinessCore\Service\BusinessFleetServiceFactory',
            'BusinessCore\Service\PaymentService' => 'BusinessCore\Service\PaymentServiceFactory',
            'BusinessCore\Service\ExtraPaymentService' => 'BusinessCore\Service\ExtraPaymentServiceFactory',
            'BusinessCore\Service\EmailService' => 'BusinessCore\Service\EmailServiceFactory',
            'BusinessCore\Listener\EmployeeApprovedListener' => 'BusinessCore\Listener\EmployeeApprovedListenerFactory',
            'BusinessCore\Listener\PaymentListener' => 'BusinessCore\Listener\PaymentListenerFactory'
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
