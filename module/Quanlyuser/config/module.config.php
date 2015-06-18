<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Quanlyuser\Controller\login' => 'Quanlyuser\Controller\loginController',
            'Quanlyuser\Controller\menu' => 'Quanlyuser\Controller\menuController'
                        
        ),
    ),
    'router' => array(
        'routes' => array(
            'quanlyuser' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/Quanlyuser[/:controller][/:action][/:id]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Quanlyuser\Controller',
                        'controller'    => 'login',
                        'action'        => 'index',
                    ),
                ),
               
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Quanlyuser' => __DIR__ . '/../view',
        ),
    ),
);
