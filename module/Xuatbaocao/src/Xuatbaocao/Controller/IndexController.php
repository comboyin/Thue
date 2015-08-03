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
use Application\Entity\ketqua;

class IndexController extends baseController
{

    public function indexAction()
    {
        return array();
    }

    public function xuatbaocaoAction()
    {
        $Mau = $this->getRequest()
            ->getPost()
            ->get('Mau');
        
        $KyThue = $this->getRequest()
            ->getPost()
            ->get('KyThue');
        $kq = null;
        $model = new XuatbaocaoModel($this->getEntityManager());
        switch ($Mau) {
            case "01/QTr-HKD":
                $kq = $model->QTrHKD01($this->getUser(), $KyThue);
                break;
            case "10/QTr-HKD":
                
                $kq = $model->QTrHKD10($this->getUser(), $KyThue);
                break;
            case "12/QTr-HKD":
                
                $kq = $model->QTrHKD12($this->getUser(), $KyThue);
                break;
            case "04/QTr-HKD":
                
                $kq = $model->QTrHKD04($this->getUser(), $KyThue);
                break;
           case "11/QTr-HKD":
                
                $kq = $model->QTrHKD11($this->getUser(), $KyThue);
                break;
            case "01/SB-HKD":
                
                $kq = $model->SBHKD01($this->getUser(), $KyThue);
                break;
            
            case "03a/SB-HKD":
                
                $kq = $model->SBHKD03a($this->getUser(), $KyThue);
                break;
        }
        
        if ($kq == null) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger('Tên mẫu không phù hợp lệ !');
        }
        echo json_encode($kq->toArray());
        return $this->response;
    }
}
