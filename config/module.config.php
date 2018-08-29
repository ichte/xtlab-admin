<?php
return [

    'router' => [
        'routes' => [
            'admin' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/admin/[:plugin/[:act/[:id/]]]',
                    'defaults' => [
                        'controller'    => \XT\Admin\Controller\AdminController::class,
                        'action'        => 'index',
                    ],
                    'constraints' => [
                        'plugin' => '[a-z][a-z]*',
                        'act'    => '[a-z][a-z]*',
                        'id'     => '[a-z0-9]*',
                    ]


                ],
            ],

        ],
    ],

    'controllers' => [
        'factories' => [
           \XT\Admin\Controller\AdminController::class => \XT\Admin\Controller\AdminController::class
        ]

    ],

    'admin_plugins' => [
        'test' => \XT\Admin\Admin\TestPlugin\HelloWorld::class
    ],
//    'view_manager' => [
//        'template_map' => [
//            'ichte/admin/admin/index'   => __DIR__ . '/../src/view/index.phtml'
//
//        ],
//    ],
];