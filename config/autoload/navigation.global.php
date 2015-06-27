<?php
/**
 * Coolcsn Zend Framework 2 Navigation Module
 * 
 * @link https://github.com/coolcsn/CsnAclNavigation for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnAclNavigation/blob/master/LICENSE BSDLicense
 * @authors Stoyan Cheresharov <stoyan@coolcsn.com>, Anton Tonev <atonevbg@gmail.com>
 */
return array(
    
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Home',
                'route' => 'home'
            ),
            array(
                'label' => 'Quản lý sổ thuế',
                'uri' => 'javascript:;',
                'pages' => array(
                    array(
                        'label' => 'Thuế môn bài',
                        'route' => 'application',
                        'action' => 'index',
                        'resource' => 'Application\Controller\Index'
                    ), array(
                        'label' => 'Thuế GTGT',
                        'route' => 'application',
                        'action' => 'index',
                        'resource' => 'Application\Controller\Index'
                    ),
                    array(
                        'label' => 'Thuế TNCN',
                        'route' => 'application',
                        'action' => 'index',
                        'resource' => 'Application\Controller\Index'
                    ),
                    array(
                        'label' => 'Thuế khác',
                        'route' => 'application',
                        'action' => 'index',
                        'resource' => 'Application\Controller\Index'
                    ),
                    array(
                        'label' => 'Dự kiến truy thu',
                        'route' => 'quanlysothue/default',
                        'controller' => 'Dukientruythu',
                        'action' => 'index',
                        'resource' => 'Quanlysothue\Controller\Dukientruythu'
                    ),
                    array(
                        'label' => 'Dự kiến thuế của năm',
                        'route' => 'quanlysothue/default',
                        'controller' => 'Dukienthuecuanam',
                        'action' => 'index',
                        'resource' => 'Quanlysothue\Controller\Dukienthuecuanam'
                    )
                    ,
                    array(
                        'label' => 'Dự kiến thuế của tháng',
                        'route' => 'quanlysothue/default',
                        'controller' => 'Dukienthuecuanam',
                        'action' => 'index',
                        'resource' => 'Quanlysothue\Controller\Dukienthuecuanam'
                    )
                    ,
                    array(
                        'label' => 'Dự kiến thuế môn bài',
                        'route' => 'quanlysothue/default',
                        'controller' => 'Dukienthuecuanam',
                        'action' => 'index',
                        'resource' => 'Quanlysothue\Controller\Dukienthuecuanam'
                    ),
                    array(
                        'label' => 'Quản lý chứng từ',
                        'route' => 'application',
                        'action' => 'index',
                        'resource' => 'Application\Controller\Index'
                    ),
                    array(
                        'label' => 'Doanh số',
                        'route' => 'application',
                        'action' => 'index',
                        'resource' => 'Application\Controller\Index'
                    )
                )
            ),
            array(
                'label' => 'Quản lý người nộp thuế',
                'uri' => 'javascript:;',
                'pages' => array(
                    array(
                        'label' => 'Người nộp thuế',
                        'route' => 'quanlynguoinopthue/default',
                        'controller' => 'Nguoinopthue',
                        'action' => 'index',
                        'resource' => 'Quanlynguoinopthue\Controller\Nguoinopthue'
                    )
                    ,
                    array(
                        'label' => 'Ngưng nghĩ',
                        'route' => 'quanlyuser',
                        'controller' => 'menu',
                        'action' => 'index',
                        'resource' => 'Quanlyuser\Controller\Menu'
                    ),
                    array(
                        'label' => 'Miễn giảm',
                        'route' => 'quanlyuser',
                        'controller' => 'menu',
                        'action' => 'index',
                        'resource' => 'Quanlyuser\Controller\Menu'
                    )
                    
                )
            ),
            
            array(
                'label' => 'Quản lý cán bộ thuế',
                'uri' => 'javascript:;',
                'pages' => array(
                    array(
                        'label' => 'Cơ quan thuế',
                        'route' => 'application',
                        'action' => 'index',
                        'resource' => 'Application\Controller\Index'
                    )
                    ,
                    array(
                        'label' => 'Cán bộ thuế',
                        'route' => 'quanlyuser',
                        'controller' => 'menu',
                        'action' => 'index',
                        'resource' => 'Quanlyuser\Controller\Menu'
                    )
                    
                )
            ),
            
            array(
                'label' => 'Báo cáo',
                'uri' => 'javascript:;',
                'pages' => array(
                    array(
                        'label' => '01/trHKD',
                        'route' => 'application',
                        'action' => 'index',
                        'resource' => 'Application\Controller\Index'
                    )
                    ,
                    array(
                        'label' => '02/trHKD',
                        'route' => 'quanlyuser',
                        'controller' => 'menu',
                        'action' => 'index',
                        'resource' => 'Quanlyuser\Controller\Menu'
                    )
                    
                )
            )
        )
    )
);



/* return array(
     'navigation' => array(
         'default' => array(
             array(
                 'label' => 'Home',
                 'route' => 'home',
				 'resource' => 'Application\Controller\Index',
				 'privilege' => 'index',
             ),
			 array(
                 'label' => 'Login',
                 'route' => 'login', 
				 'controller' => 'Index',
				 'action'     => 'login',
				 'resource'	  => 'CsnUser\Controller\Index',
				 'privilege'  => 'login',
             ),
			 array(
                 'label' => 'User',
                 'route' => 'user', 
				 'controller' => 'Index',
				 'action'     => 'index',
				 'resource'	  => 'CsnUser\Controller\Index',
				 'privilege'  => 'index',
             ),
             array(
                 'label' => 'Registration',
                 'route' => 'registration', 
				 'controller' => 'Registration',
				 'action'     => 'index',
				 'resource'	  => 'CsnUser\Controller\Registration',
				 'privilege'  => 'index',
				 'title'	  => 'Registration Form'
             ),
             array(
                 'label' => 'Edit profile',
                 'route' => 'editProfile', 
				 'controller' => 'Registration',
				 'action'     => 'editProfile',
				 'resource'	  => 'CsnUser\Controller\Registration',
				 'privilege'  => 'editProfile',
             ),
			array(
				'label' => 'Zend',
				'uri'   => 'http://framework.zend.com/',
				'resource' => 'Zend',
				'privilege'	=>	'uri'
			),
			
			// uncomment if you have the CsnCms module installed 
			array(
                 'label' => 'CMS',
                 'route' => 'csn-cms', 
				 'controller' => 'Index',
				 'action'     => 'index',
				 'resource'	  => 'CsnCms\Controller\Index',
				 'privilege'  => 'index'
             ),
			
            array(
                 'label' => 'Logout',
                 'route' => 'logout', 
				 'controller' => 'Index',
				 'action'     => 'logout',
				 'resource'	  => 'CsnUser\Controller\Index',
				 'privilege'  => 'logout'
             ),
			
		 ),
	 )
); */ 