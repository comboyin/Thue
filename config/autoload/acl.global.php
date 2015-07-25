<?php
// http://p0l0.binware.org/index.php/2012/02/18/zend-framework-2-authentication-acl-using-eventmanager/
// First I created an extra config for ACL (could be also in module.config.php, but I prefer to have it in a separated file)
return array(
    'acl' => array(
        'roles' => array(
            'guest' => null,
            'canbothue' => 'guest',
            'doitruong' => 'canbothue',
            'chicucthue' => 'doitruong',
            'admin'     => 'chicucthue'
        ),
        
        'resources' => array(
            'allow' => array(
                /*  'controller_name' => array(
                 * 'action_name' => 'name_role',
                 * .
                 * .
                 * .
                 * )*/
                'Quanlyuser\Controller\Login' => array(
                    'index'=>'guest',
                    'all'=>'canbothue'
                ),
                'Application\Controller\Index' =>array(
                    //bo trong la khong cho phep
                    'all'=>'canbothue'
                ),
                'Application\Controller\Service' =>array(
                    //bo trong la khong cho phep
                    'all'=>'canbothue'
                ),
                'Quanlyuser\Controller\Menu' =>array(
                    'all'=>'canbothue'
                ),
                
                
                
                //Module Quanlysothue
                'Quanlysothue\Controller\Dukientruythu' => array(
                    'all' => 'canbothue'
                ),
                'Quanlysothue\Controller\Dukienthue' => array(
                    'all' => 'canbothue'
                ),
                'Quanlysothue\Controller\Chungtu' => array(
                    'all' => 'canbothue'
                ),
                'Quanlysothue\Controller\Dukienthuecuanam' => array(
                    'all' => 'canbothue'
                )
                ,
                'Quanlysothue\Controller\Dukienthuecuathang' => array(
                    'all' => 'canbothue'
                )
                ,
                'Quanlysothue\Controller\Dukienthuemonbai' => array(
                    'all' => 'canbothue'
                ),
                //sothue
                'Quanlysothue\Controller\Thuekhoan' => array(
                    'all' => 'canbothue'
                ),
                'Quanlysothue\Controller\Thuemonbai' => array(
                    'all' => 'canbothue'
                ),
                'Quanlysothue\Controller\Thuetruythu' => array(
                    'all' => 'canbothue'
                ),
                'Quanlysothue\Controller\Thuetieuthudacbiet' => array(
                    'all' => 'canbothue'
                ),
                'Quanlysothue\Controller\Thuebaovemoitruong' => array(
                    'all' => 'canbothue'
                ),
                'Quanlysothue\Controller\Thuetainguyen' => array(
                    'all' => 'canbothue'
                ),

                'Quanlysothue\Controller\Xuatbangke' => array(
                    'all' => 'canbothue'
                ),

				
				////Module Quanlynguoinopthue
                'Quanlynguoinopthue\Controller\Nguoinopthue' => array(
                    'all' => 'canbothue'
                ),
                'Quanlynguoinopthue\Controller\Index' => array(
                    'all' => 'canbothue'
                ),
                'Quanlynguoinopthue\Controller\Thongtinngungnghi' => array(
                    'all' => 'canbothue'
                ),
                'Quanlynguoinopthue\Controller\Miengiam' => array(
                    'all' => 'canbothue'
                ),
                
                //Module Quanlycanbothue
                'Quanlycanbothue\Controller\Canbothue' => array(
                    'all' => 'doitruong'
                ),
                'Quanlycanbothue\Controller\Thongtincanhan' => array(
                    'all' => 'canbothue'
                )
                ,
                'Xuatbaocao\Controller\Index' => array(
                    'all' => 'canbothue'
                ),
                //end
            )
        )
    )
);
