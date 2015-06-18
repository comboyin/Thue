<?php
return array(
    'layouts' => array(
        'Application' => array(
            // danh sach controller
            'controllers' => array(
                'Index' => array(
                    'actions' => array(
                        'index' => 'layout/layout'
                    ),
                    // default toan controller
                    'default' => 'layout/layout'
                )
            ),
            // default toan module
            'default' => 'layout/layout'
        ),
        'Quanlyuser' => array(
            // danh sach controller
            'controllers' => array(
                'Login' => array(
                    'actions' => array(),
                    'default' => 'layout/layoutlogin'
                )
            ),
            'default' => 'layout/layout'
        ),
        'Quanlycanbothue' => array(
            // danh sach controller
            'controllers' => array(
                'Index' => array(
                    'actions' => array(),
                    'default' => 'layout/layout'
                )
            ),
            'default' => 'layout/layout'
        ),
        'Quanlynguoinopthue' => array(
            // danh sach controller
            'controllers' => array(
                'Index' => array(
                    'actions' => array(),
                    'default' => 'layout/layout'
                )
            ),
            'default' => 'layout/layout'
        ),
        'Quanlynguoinopthue' => array(
            // danh sach controller
            'controllers' => array(
                'Index' => array(
                    'actions' => array(),
                    'default' => 'layout/layout'
                )
            ),
            'default' => 'layout/layout'
        )
    )
)
;
