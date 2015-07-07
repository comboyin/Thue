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
use Quanlynguoinopthue\Form\formThongTinNgungNghi;
use Application\Entity\ketqua;
use Application\Unlity\Unlity;
use Application\Entity\thongtinngungnghi;

class ThongtinngungnghiController extends baseController
{
    
    // Load danh sach danh bạ
    public function indexAction()
    {
        return array();
    }
    
    // ajax
    public function ajaxDanhSachThongTinNgungNghiAction()
    {
        $MaSoThue = $this->getRequest()
            ->getQuery()
            ->get('MaSoThue');
        
        $model = new thongtinngungnghiModel($this->getEntityManager());
        
        $kq = $model->danhSachThongTinNgungNghi($MaSoThue);
        
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function xoaAction()
    {
        $kq = new ketqua();
        $MaTTNgungNghi = $this->getRequest()
            ->getPost()
            ->get("MaTTNgungNghi");
        
        $tim = $this->getEntityManager()->find('Application\Entity\thongtinngungnghi', $MaTTNgungNghi);
        if ($tim != null) {
            
            $model = new thongtinngungnghiModel($this->getEntityManager());
            $kq = $model->remove($tim);
        } else {
            $kq->setKq(false);
            $kq->setMessenger("Không tìm thấy !");
        }
        
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function suaAction()
    {
        $kq = new ketqua();
        $MaTTNgungNghi = $this->getRequest()
            ->getPost()
            ->get("MaTTNgungNghi");
        $MaSoThue = $this->getRequest()
            ->getPost()
            ->get("MaSoThue");
        $TuNgay = Unlity::ConverDate('d-m-Y', $this->getRequest()
            ->getPost()
            ->get("TuNgay"), 'Y-m-d');
        $DenNgaytem = $this->getRequest()->getPost()->get("DenNgay");
        
        $DenNgay = (strlen($DenNgaytem) ==0) ? $DenNgaytem : Unlity::ConverDate('d-m-Y', $DenNgaytem, 'Y-m-d');
        $LyDo = $this->getRequest()
            ->getPost()
            ->get("LyDo");
        $NgayNopDon = Unlity::ConverDate('d-m-Y', $this->getRequest()
            ->getPost()
            ->get("NgayNopDon"), 'Y-m-d');
        
        $form = new formThongTinNgungNghi();
        
        $form->setData($this->getRequest()
            ->getPost());
        if ($form->isValid()) {
            // kiem tra
            if ((strlen($DenNgaytem) == 0)?true:strtotime($TuNgay) < strtotime($DenNgay)) {
                if (strtotime($NgayNopDon) > strtotime($TuNgay)) {
                    
                    
                    
                    $tim = $this->getEntityManager()->find('Application\Entity\thongtinngungnghi', $MaTTNgungNghi);
                    if($tim!=null){
                        // Them vao CSDl
                        $thongtinngungnghiModel = new thongtinngungnghiModel($this->getEntityManager());
                        $thongtinngungnghi = $tim;
                        $thongtinngungnghi->setNguoinopthue($this->getEntityManager()
                            ->find('Application\Entity\nguoinopthue', $MaSoThue));
                        $thongtinngungnghi->setTuNgay($TuNgay);
                        
                        $thongtinngungnghi->setDenNgay($DenNgay==""?null:$DenNgay);
                        $thongtinngungnghi->setNgayNopDon($NgayNopDon);
                        $thongtinngungnghi->setLyDo($LyDo);
                        $kq = $thongtinngungnghiModel->merge($thongtinngungnghi);
                    }
                    else{
                        $kq->setKq(false);
                        $kq->setMessenger("Không tìm thấy !");
                    }
                    
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger("'Ngày nộp đơn' phải trước 'Từ ngày' !");
                }
            } else {
                $kq->setKq(false);
                $kq->setMessenger("'Từ ngày' phải trước 'Đến ngày' !");
            }
        } else {
            $kq->setKq(false);
            $kq->setMessenger($this->getErrorMessengerForm($form));
        }
        
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function themAction()
    {
        $kq = new ketqua();
        $MaSoThue = $this->getRequest()
            ->getPost()
            ->get("MaSoThue");
        $TuNgay = Unlity::ConverDate('d-m-Y', $this->getRequest()
            ->getPost()
            ->get("TuNgay"), 'Y-m-d');
        $DenNgaytem = $this->getRequest()->getPost()->get("DenNgay");
        
        $DenNgay = (strlen($DenNgaytem) ==0) ? $DenNgaytem : Unlity::ConverDate('d-m-Y', $DenNgaytem, 'Y-m-d');
        $LyDo = $this->getRequest()
            ->getPost()
            ->get("LyDo");
        $NgayNopDon = Unlity::ConverDate('d-m-Y', $this->getRequest()
            ->getPost()
            ->get("NgayNopDon"), 'Y-m-d');
        
        $form = new formThongTinNgungNghi();
        
        $form->setData($this->getRequest()
            ->getPost());
        if ($form->isValid()) {
            // kiem tra
            
            if ((strlen($DenNgaytem) == 0)?true:strtotime($TuNgay) < strtotime($DenNgay)) {
                if (strtotime($NgayNopDon) < strtotime($TuNgay)) {
                    
                    // Them vao CSDl
                    $thongtinngungnghiModel = new thongtinngungnghiModel($this->getEntityManager());
                    $thongtinngungnghi = new thongtinngungnghi();
                    $thongtinngungnghi->setNguoinopthue($this->getEntityManager()
                        ->find('Application\Entity\nguoinopthue', $MaSoThue));
                    $thongtinngungnghi->setTuNgay($TuNgay);
                    $thongtinngungnghi->setDenNgay($DenNgay==""?null:$DenNgay);
                    $thongtinngungnghi->setNgayNopDon($NgayNopDon);
                    $thongtinngungnghi->setLyDo($LyDo);
                    $thongtinngungnghi->setMaTTNgungNghi($thongtinngungnghiModel->createMaTTNgungNghi());
                    $kq = $thongtinngungnghiModel->them($thongtinngungnghi);
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger("'Ngày nộp đơn' phải trước 'Từ ngày' !");
                }
            } else {
                $kq->setKq(false);
                $kq->setMessenger("'Từ ngày' phải trước 'Đến ngày' !");
            }
        } else {
            $kq->setKq(false);
            $kq->setMessenger($this->getErrorMessengerForm($form));
        }
        
        echo json_encode($kq->toArray());
        return $this->response;
    }
}
