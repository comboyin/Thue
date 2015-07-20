<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Quanlyuser for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Quanlyuser\Controller;

use Application\base\baseController;
use Application\Models\chatModel;


class menuController extends baseController
{
    
    // login
    public function indexAction()
    {
        $model = new chatModel($this->getEntityManager());
        
        $dsChat = $model->DanhSachChat($this->getUser());
        return array(
            'dsChat' => $dsChat->getObj()
        );
    }

}
