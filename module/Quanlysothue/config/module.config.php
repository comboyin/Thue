<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Quanlysothue\Controller\Index' => 'Quanlysothue\Controller\IndexController',
            'Quanlysothue\Controller\Dukientruythu' => 'Quanlysothue\Controller\DukientruythuController',
            'Quanlysothue\Controller\Dukienthuecuanam' => 'Quanlysothue\Controller\DukienthuecuanamController',
            'Quanlysothue\Controller\Chungtu' => 'Quanlysothue\Controller\ChungtuController',
            'Quanlysothue\Controller\Dukienthuemonbai' => 'Quanlysothue\Controller\DukienthuemonbaiController',
            'Quanlysothue\Controller\Dukienthuecuathang' => 'Quanlysothue\Controller\DukienthuecuathangController',
            
            
            'Quanlysothue\Controller\Thuekhoan' => 'Quanlysothue\Controller\ThuekhoanController',
            'Quanlysothue\Controller\Thuetruythu' => 'Quanlysothue\Controller\ThuetruythuController',
            'Quanlysothue\Controller\Thuemonbai' => 'Quanlysothue\Controller\ThuemonbaiController',
            'Quanlysothue\Controller\Thuetainguyen' => 'Quanlysothue\Controller\ThuetainguyenController',
            'Quanlysothue\Controller\Thuebaovemoitruong' => 'Quanlysothue\Controller\ThuebaovemoitruongController',
            'Quanlysothue\Controller\Thuetieuthudacbiet' => 'Quanlysothue\Controller\ThuetieuthudacbietController'
            
        ),
    ),
    'router' => array(
        'routes' => array(
            'quanlysothue' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/quanlysothue',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Quanlysothue\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Quanlysothue' => __DIR__ . '/../view',
        ),
    ),
);
