<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\dukienthuecuanamModel;
use Quanlysothue\Froms\formDuKienThueCuaNam;
use Application\Entity\ketqua;
use Zend\Http\Request;
use Zend\Form\Form;
use Quanlysothue\Froms\UploadForm;
use Quanlysothue\Excel\ImportExcelDuKienThueCuaNam;
use Application\Models\nganhModel;
use Application\Entity\dukienthue;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class DukienthuecuanamController extends baseController
{

    /**
     * Hiển thị danh sách dự kiến của năm theo kỳ thuế năm hiện tại.
     */
    public function indexAction()
    {
        $formUp = new UploadForm('upload-form');
        // Lấy năm gần nhất
        // to String 'Y'
        $today = (new \DateTime())->format('Y');
        
        $dukienthuecuanamModel = new dukienthuecuanamModel($this->getEntityManager());
        
        // danh sach theo nam
        $dsdkthuecuanam = $dukienthuecuanamModel->dsdukienthuecuanam($today, $this->getUser());
        
        return array(
            'formUp'=>$formUp,
            'dsDuKienThueCuaNam' => $dsdkthuecuanam->getObj()
        );
    }
    
    // ajax lay danh sach NNT


    public function themAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        /* @var $request Request */
        /* @var $form Form */
        $request = $this->getRequest();
        $kq = new ketqua();
        $dukienthuenam = new dukienthue();
        $form = new formDuKienThueCuaNam();
        $form->setData($request->getPost());
        
        // validation thanh cong
        if ($form->isValid()) {
            $post = $request->getPost();
            $MaSoThue = $post->get('MaSoThue');
            
            $kt = new nguoinopthueModel($this->getEntityManager());
            if($kt->ktNNT($MaSoThue, $this->getUser()) == true)
            {
                $nganhModel = new nganhModel($this->getEntityManager());
                // them
                $KyThue = $post->get('KyThue');
                
                $TieuMuc = $post->get('TieuMuc');
                $DoanhThuChiuThue = $post->get('DoanhThuChiuThue');
                $TiLeTinhThue = $nganhModel->getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue, $TieuMuc);
                $ThueSuat = $post->get('ThueSuat');
                $TenGoi = $post->get('TenGoi');
                $SanLuong = $post->get('SanLuong');
                $GiaTinhThue = $post->get('GiaTinhThue');
                $SoTien = $post->get('SoTien');
                 
                $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
                
                $dukienthuenam->setKyThue($KyThue);
                $dukienthuenam->setNguoinopthue($nguoinopthue);
                $dukienthuenam->setMuclucngansach($muclucngansach);
                $dukienthuenam->setDoanhThuChiuThue($DoanhThuChiuThue);
                $dukienthuenam->setTiLeTinhThue($TiLeTinhThue);
                $dukienthuenam->setThueSuat($ThueSuat);
                
                if($TenGoi=="")
                    $dukienthuenam->setTenGoi(null);
                else
                    $dukienthuenam->setTenGoi($TenGoi);
                
                if($SanLuong==0)
                    $dukienthuenam->setSanLuong(null);
                else
                    $dukienthuenam->setSanLuong($SanLuong);
                
                if($GiaTinhThue==0)
                    $dukienthuenam->setGiaTinhThue(null);
                else
                    $dukienthuenam->setGiaTinhThue($GiaTinhThue);
                
                $dukienthuenam->setSoTien($SoTien);
                $dukienthuenam->setNgayPhaiNop(null);
                $dukienthuenam->setTrangThai(0);
                
                $dukienthuenamModel = new dukienthuecuanamModel($this->getEntityManager());
                $kq = $dukienthuenamModel->them($dukienthuenam);
            } else { 
                $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn.";
                $kq->setKq(false);
                $kq->setMessenger($mss);
            } 

        }  // validation lỗi
        else {
            $mss = $this->getErrorMessengerForm($form);
            $kq->setKq(false);
            $kq->setMessenger($kq->getMessenger() . "\n" . $mss);
        }
        
        // trả về json
        echo json_encode($kq->toArray());
        return $this->response;
    }
    
    // ajax
    public function xoaAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        $MaSoThue = $post->get('_MaSoThue');
        $KyThue = $post->get('_KyThue');
        $TieuMuc = $post->get('_TieuMuc');
        
        $kq = new ketqua();
        $mss = "";
        
        // validation
        // kiem tra masothue
        
        // xoa trong csdl
        $model = new dukienthuecuanamModel($this->getEntityManager());
        $dukienthuenam = $model->findByID_($KyThue, $MaSoThue, $TieuMuc)->getObj();
        if ($dukienthuenam != null) {
            $kq->setKq(true);
            $kq = $model->remove($dukienthuenam);
        } else {
            $kq->setKq(false);
            $mss .= 'Không tìm được dự kiến thuế năm !';
        }
        
        $kq->setMessenger($kq->getMessenger() . $mss);
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function suaAction()
    {
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
            $post = $request->getPost();
            $kq = new ketqua();
            $dukienthuenam = new dukienthue();
            $form = new formDuKienThueCuaNam();
            $form->setData($post);
            
            // validation thanh cong
            if ($form->isValid()) {
                $MaSoThue = $post->get('MaSoThue');
                $kt = new nguoinopthueModel($this->getEntityManager());
                if($kt->ktNNT($MaSoThue, $this->getUser()) == true)
                {
                    // sua
                    
                    // tim dukienthue
                    $nganhModel = new nganhModel($this->getEntityManager());
                    $dukienthuenamModel = new dukienthuecuanamModel($this->getEntityManager());
                    /* @var $dukienthuenam dukienthue */
                    $dukienthuenam = $dukienthuenamModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))
                    ->getObj();
                    
                    $KyThue = $post->get('_KyThue');
                    
                    $TieuMuc = $post->get('TieuMuc');
                    $DoanhThuChiuThue = $post->get('DoanhThuChiuThue');
                    $TiLeTinhThue = $nganhModel->getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue, $TieuMuc);
                    $ThueSuat = $post->get('ThueSuat');
                    $TenGoi = $post->get('TenGoi');
                    $SanLuong = $post->get('SanLuong');
                    $GiaTinhThue = $post->get('GiaTinhThue');
                    $SoTien = $post->get('SoTien');
                    
                    
                    $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                    $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
                    
                    $dukienthuenam->setKyThue($KyThue);
                    $dukienthuenam->setNguoinopthue($nguoinopthue);
                    $dukienthuenam->setMuclucngansach($muclucngansach);
                    $dukienthuenam->setDoanhThuChiuThue($DoanhThuChiuThue);
                    $dukienthuenam->setTiLeTinhThue($TiLeTinhThue);
                    $dukienthuenam->setThueSuat($ThueSuat);
                    
                    if($TenGoi=="")
                        $dukienthuenam->setTenGoi(null);
                    else
                        $dukienthuenam->setTenGoi($TenGoi);
                    
                    if($SanLuong==0)
                        $dukienthuenam->setSanLuong(null);
                    else
                        $dukienthuenam->setSanLuong($SanLuong);
                    
                    if($GiaTinhThue==0)
                        $dukienthuenam->setGiaTinhThue(null);
                    else
                        $dukienthuenam->setGiaTinhThue($GiaTinhThue);
                    
                    $dukienthuenam->setSoTien($SoTien);
                    $dukienthuenam->setNgayPhaiNop(null);
                    $dukienthuenam->setTrangThai(0);
                    
                    $kq = $dukienthuenamModel->merge($dukienthuenam);
                } else { 
                    $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn hoặc đã nghĩ bỏ kinh doanh không thể lập dự kiến thuế.";
                    $kq->setKq(false);
                    $kq->setMessenger($mss);
                } 
            }             

            // validation lỗi
            else {
                $mss = $this->getErrorMessengerForm($form);
                $kq->setKq(false);
                $kq->setMessenger($mss);
            }
            
            // trả về json
            echo json_encode($kq->toArray());
            return $this->response;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            echo json_encode($kq->toArray());
        }
    }

    public function xoanhieuAction()
    {
        $post = $this->getRequest()->getPost();
        $KyThue = $post->get('_KyThue');
        $MaSoThueData = $post->get("MaSoThueData");
        $TieuMucData = $post->get("TieuMucData");
        $model = new dukienthuecuanamModel($this->getEntityManager());
        $dem = 0;
        for($i=0;$i<count($MaSoThueData);$i++)
        {
            $dukienthue = $model->findByID_($KyThue, $MaSoThueData[$i], $TieuMucData[$i])->getObj();
            
            if($dukienthue !=null)
            {
                $model->remove($dukienthue);
                $dem++;
            }
            
        }
        $kq = new ketqua();
        $kq->setKq(true);
        $kq->setMessenger("Đã xóa ".$dem . " mục");
        echo json_encode($kq->toArray());
        return $this->response;
    }
    /**
     * Trả về danh sách dự kiến thuế của năm
     *   */
    public function dsDKTNJsonAction(){   
        
        $kq = new ketqua();
        try {
            $get = $this->getRequest()->getQuery();
            
            $dukienthuecuanamModel = new dukienthuecuanamModel($this->getEntityManager());
            
            // danh sach theo nam
            $dsdkthuecuanam = $dukienthuecuanamModel->dsDKTNJson($get->get('KyThue'), $this->getUser());
            
               
            echo json_encode($dsdkthuecuanam);
            return $this->response;
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            echo json_encode($kq->toArray());
            return $this->response;
        }
        
        
    }
    
    public function loadTyLeTinhThueAction(){
         
        $MaSoThue = $this->getRequest()->getQuery()->get('MaSoThue');
        $TieuMuc = $this->getRequest()->getQuery()->get('TieuMuc');
         
        $nganhModel = new nganhModel($this->getEntityManager());
        $TyLeTinhThue =  $nganhModel->getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue, $TieuMuc);
        echo json_encode(array(
            'TyLeTinhThue' => $TyLeTinhThue
        ));
        return $this->response;
    }
}