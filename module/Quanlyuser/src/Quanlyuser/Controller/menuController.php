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
        
        //thongbao
        $temp = $model->DemTamNghi($this->getUser());
        $demtn = ($temp->getKq()==true)?$temp->getObj():0;
        
        $temp = $model->DemChuaDK($this->getUser());
        $demchuadk = ($temp->getKq()==true)?$temp->getObj():0;
        
        $temp = $model->ThueMB($this->getUser());
        $thuemb = ($temp->getKq()==true)?$temp->getObj():0;
        
        $temp = $model->ThueKhoan($this->getUser());
        $thuekhoan = ($temp->getKq()==true)?$temp->getObj():0;
        
        $temp = $model->ThueTTDB($this->getUser());
        $thuettdb = ($temp->getKq()==true)?$temp->getObj():0;
        
        $temp = $model->ThueTN($this->getUser());
        $thuetn = ($temp->getKq()==true)?$temp->getObj():0;
        
        $temp = $model->ThueBVMT($this->getUser());
        $thuebvmt = ($temp->getKq()==true)?$temp->getObj():0;
        
        $temp = $model->DKThue($this->getUser());
        $dkthue = ($temp->getKq()==true)?$temp->getObj():0;
        
        return array(
            'dsChat' => $dsChat->getObj(),
            'demtn' => $demtn,
            'demchuadk' => $demchuadk,
            'thuemb' => $thuemb,
            'thuekhoan' => $thuekhoan,
            'thuettdb' => $thuettdb,
            'thuetn' => $thuetn,
            'thuebvmt' => $thuebvmt,
            'dkthue' => $dkthue
        );
    }

}
