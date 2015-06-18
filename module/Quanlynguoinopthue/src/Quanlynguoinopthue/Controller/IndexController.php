<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Quanlynguoinopthue for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Quanlynguoinopthue\Controller;


use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\base\baseController;


class IndexController extends baseController
{
    
    //Load danh sach danh bạ
    public function indexAction()
    {
        
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        
        $danhsach = $nguoinopthueModel->getDanhSachByIdentity($this->getUser());
        
        var_dump($danhsach);
        return $this->response;
    }
    
    

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/foo
        return array();
    }
}
