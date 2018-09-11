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
                        'id'     => '[a-zA-Z0-9]*',
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
        //'test' => \XT\Admin\Admin\TestPlugin\HelloWorld::class
    ],

];