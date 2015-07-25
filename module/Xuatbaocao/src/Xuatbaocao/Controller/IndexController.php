<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Xuatbaocao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Xuatbaocao\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Xuatbaocao\Model\XuatbaocaoModel;
use Application\base\baseController;

class IndexController extends baseController
{
    public function indexAction()
    {
        return array();
    }

    public function QTrHKD01Action(){
        $model = new XuatbaocaoModel($this->getEntityManager());
        $kq = $model->QTrHKD01($this->getUser());
        echo json_encode($kq->toArray());
        return $this->response;
    }
}
