<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Quanlynguoinopthue for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Quanlynguoinopthue\Controller;



use Application\base\baseController;
use Application\Models\thongtinngungnghiModel;


class ThongtinngungnghiController extends baseController
{
    
    //Load danh sach danh bạ
    public function indexAction()
    {
        
       return array();
    }
    
    
    //ajax
    public function ajaxDanhSachThongTinNgungNghiAction(){
        $MaSoThue = $this->getRequest()->getQuery()->get('MaSoThue');
        
        $model = new thongtinngungnghiModel($this->getEntityManager());
        
        $kq = $model->danhSachThongTinNgungNghi($MaSoThue);
        
        echo json_encode($kq->toArray());
        return $this->response;
        
    }
    
    public function xoaAction()
    {
        
    }
    
    public function themAction(){
        
        $MaSoThue = $this->getRequest()->getPost()->get("_MaSoThue");
        $TuNgay = $this->getRequest()->getPost()->get("TuNgay");
        $DenNgay = $this->getRequest()->getPost()->get("DenNgay");
        $LyDo = $this->getRequest()->getPost()->get("LyDo");
        $NgayNopDon = $this->getRequest()->getPost()->get("NgayNopDon");
        
        $form = new 
    }
    
    
    public function suaAction(){
        
    }
    
}
