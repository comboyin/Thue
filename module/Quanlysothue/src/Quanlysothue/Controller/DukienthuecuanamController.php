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
            'formUp' => $formUp,
            'dsDuKienThueCuaNam' => $dsdkthuecuanam->getObj()
        );
    }
    
    // ajax lay danh sach NNT
    public function themAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        $kq = new ketqua();
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
           
            $dukienthuenam = new dukienthue();
            $form = new formDuKienThueCuaNam();
            $form->setData($request->getPost());
            
            // validation thanh cong
            if ($form->isValid()) {
                $post = $request->getPost();
                $MaSoThue = $post->get('MaSoThue');
                
                $kt = new nguoinopthueModel($this->getEntityManager());
                if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
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
                    
                    if ($TieuMuc == '1003' || $TieuMuc == '1701') // TNCN&GTGT
                    {
                        if ($DoanhThuChiuThue * 12 > 100000000) {
                            $SoTien = intval($DoanhThuChiuThue * $TiLeTinhThue);
                        } else
                            $SoTien = 0;
                    } else 
                        if ($TieuMuc == '2601') // BVMT
                            $SoTien = intval($SanLuong * $GiaTinhThue);
                        else 
                            if ($TieuMuc == '3801') // TN
                                $SoTien = intval($SanLuong * $GiaTinhThue * $ThueSuat);
                            else 
                                if ($TieuMuc == '1757') // TTDB
                                    $SoTien = intval($GiaTinhThue * $ThueSuat);
                                else
                                    $SoTien = $post->get('SoTien');
                    
                    $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                    $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
                    
                    $dukienthuenam->setKyThue($KyThue);
                    $dukienthuenam->setNguoinopthue($nguoinopthue);
                    $dukienthuenam->setMuclucngansach($muclucngansach);
                    $dukienthuenam->setDoanhThuChiuThue($DoanhThuChiuThue);
                    $dukienthuenam->setTiLeTinhThue($TiLeTinhThue);
                    $dukienthuenam->setThueSuat($ThueSuat);
                    
                    if ($TenGoi == "" && $TieuMuc != '2601' && $TieuMuc != '3801' && $TieuMuc != '1757')
                        $dukienthuenam->setTenGoi(null);
                    else
                        $dukienthuenam->setTenGoi($TenGoi);
                    
                    if ($SanLuong == 0 && $TieuMuc != '2601' && $TieuMuc != '3801')
                        $dukienthuenam->setSanLuong(null);
                    else
                        $dukienthuenam->setSanLuong($SanLuong);
                    
                    if ($GiaTinhThue == 0 && $TieuMuc != '2601' && $TieuMuc != '3801' && $TieuMuc != '1757')
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
            }  else {
                $mss = $this->getErrorMessengerForm($form);
                $kq->setKq(false);
                $kq->setMessenger($mss);
            }
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
        }
        // trả về json
        echo json_encode($kq->toArray());
        return $this->response;
    }
    
    // ajax
    public function xoaAction()
    {
        $kq = new ketqua();
        try {
            $request = $this->getRequest();
            $post = $request->getPost();
            $MaSoThue = $post->get('_MaSoThue');
            $KyThue = $post->get('_KyThue');
            $TieuMuc = $post->get('_TieuMuc');
    
            // xoa trong csdl
            $model = new dukienthuecuanamModel($this->getEntityManager());
            $dukienthuenam = $model->findByID_($KyThue, $MaSoThue, $TieuMuc)->getObj();
            //kt ton tai
            if ($dukienthuenam != null) {
                // kiem tra masothue
                $kt = new nguoinopthueModel($this->getEntityManager());
                if($kt->ktNNT($MaSoThue, $this->getUser()) == true)
                {
                    $kq->setKq(true);
                    $kq = $model->remove($dukienthuenam);
                    
                } else {
                    $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn.";
                    $kq->setKq(false);
                    $kq->setMessenger($mss);
                }     
            } else {
                $kq->setKq(false);
                $kq->setMessenger('Không tìm được dự kiến thuế năm !');
            }
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
        }
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function suaAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        $kq = new ketqua();
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
            $post = $request->getPost();
            $dukienthuenam = new dukienthue();
            $form = new formDuKienThueCuaNam();
            $form->setData($post);
            
            // validation thanh cong
            if ($form->isValid()) {
                $dukienthuenamModel = new dukienthuecuanamModel($this->getEntityManager());
                /* @var $dukienthuenam dukienthue */
                $dukienthuenam = $dukienthuenamModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))
                ->getObj();
                if ($dukienthuenam != null) {
                
                    $MaSoThue = $post->get('MaSoThue');
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                        // sua
                        
                        // tim dukienthue
                        $nganhModel = new nganhModel($this->getEntityManager());
                        
                        $KyThue = $post->get('_KyThue');
                        
                        $TieuMuc = $post->get('TieuMuc');
                        $DoanhThuChiuThue = $post->get('DoanhThuChiuThue');
                        $TiLeTinhThue = $nganhModel->getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue, $TieuMuc);
                        $ThueSuat = $post->get('ThueSuat');
                        $TenGoi = $post->get('TenGoi');
                        $SanLuong = $post->get('SanLuong');
                        $GiaTinhThue = $post->get('GiaTinhThue');
                        
                        if ($TieuMuc == '1003' || $TieuMuc == '1701') // TNCN&GTGT
                        {
                            if ($DoanhThuChiuThue * 12 > 100000000) {
                                $SoTien = intval($DoanhThuChiuThue * $TiLeTinhThue);
                            } else
                                $SoTien = 0;
                        } else 
                            if ($TieuMuc == '2601') // BVMT
                                $SoTien = intval($SanLuong * $GiaTinhThue);
                            else 
                                if ($TieuMuc == '3801') // TN
                                    $SoTien = intval($SanLuong * $GiaTinhThue * $ThueSuat);
                                else 
                                    if ($TieuMuc == '1757') // TTDB
                                        $SoTien = intval($GiaTinhThue * $ThueSuat);
                                    else
                                        $SoTien = $post->get('SoTien');
                        
                        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                        $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
                        
                        $dukienthuenam->setKyThue($KyThue);
                        $dukienthuenam->setNguoinopthue($nguoinopthue);
                        $dukienthuenam->setMuclucngansach($muclucngansach);
                        $dukienthuenam->setDoanhThuChiuThue($DoanhThuChiuThue);
                        $dukienthuenam->setTiLeTinhThue($TiLeTinhThue);
                        $dukienthuenam->setThueSuat($ThueSuat);
                        
                        if ($TenGoi == "" && $TieuMuc != '2601' && $TieuMuc != '3801' && $TieuMuc != '1757')
                            $dukienthuenam->setTenGoi(null);
                        else
                            $dukienthuenam->setTenGoi($TenGoi);
                        
                        if ($SanLuong == 0 && $TieuMuc != '2601' && $TieuMuc != '3801')
                            $dukienthuenam->setSanLuong(null);
                        else
                            $dukienthuenam->setSanLuong($SanLuong);
                        
                        if ($GiaTinhThue == 0 && $TieuMuc != '2601' && $TieuMuc != '3801' && $TieuMuc != '1757')
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
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger('Không tìm được dự kiến truy thu!');
                }    
                    
            } else {
                $mss = $this->getErrorMessengerForm($form);
                $kq->setKq(false);
                $kq->setMessenger($mss);
            }
        } catch (\Exception $e) {
            
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());

        }
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function xoanhieuAction()
    {
        $kq = new ketqua();
        try {
            $this->getEntityManager()
                ->getConnection()
                ->beginTransaction();
            
            $post = $this->getRequest()->getPost();
            $KyThue = $post->get('_KyThue');
            $MaSoThueData = $post->get("MaSoThueData");
            $TieuMucData = $post->get("TieuMucData");
            $model = new dukienthuecuanamModel($this->getEntityManager());
            $dem = 0;
            
            
            for ($i = 0; $i < count($MaSoThueData); $i ++) {
                
                $dukienthue = $model->findByID_($KyThue, $MaSoThueData[$i], $TieuMucData[$i])->getObj();
                if ($dukienthue != null) {
                    // kiem tra nguoi nop thue co thuoc quyen quan ly cua cbt do khong ?
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThueData[$i], $this->getUser()) == true) 
                    {
                        $model->remove($dukienthue);
                        $dem ++;
                    } else {
                        $mss = "Người nộp thuế có mã ".$MaSoThueData[$i]." không thuộc quyền quản lý của bạn.";
                        $kq->setKq(false);
                        $kq->setMessenger($mss);
                        $this->getEntityManager()
                            ->getConnection()
                            ->rollBack();
                        echo json_encode($kq->toArray());
                        return $this->response;
                    }
                } else {
                    $mss = "Không tìm được dự kiến truy thu có mã ".$MaSoThueData[$i]." !";
                    $kq->setKq(false);
                    $kq->setMessenger($mss);
                    $this->getEntityManager()
                    ->getConnection()
                    ->rollBack();
                    echo json_encode($kq->toArray());
                    return $this->response;
                
                }
            }
            
            $kq->setKq(true);
            $kq->setMessenger("Đã xóa " . $dem . " mục");
            
            $this->getEntityManager()
                ->getConnection()
                ->commit();
        } catch (\Exception $e) {
            $this->getEntityManager()
                ->getConnection()
                ->rollBack();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
        }
        echo json_encode($kq->toArray());
        return $this->response;
    }

    /**
     * Trả về danh sách dự kiến thuế của năm
     */
    public function dsDKTNJsonAction()
    {
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

    public function loadTyLeTinhThueAction()
    {
        $MaSoThue = $this->getRequest()
            ->getQuery()
            ->get('MaSoThue');
        $TieuMuc = $this->getRequest()
            ->getQuery()
            ->get('TieuMuc');
        
        $nganhModel = new nganhModel($this->getEntityManager());
        $TyLeTinhThue = $nganhModel->getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue, $TieuMuc);
        echo json_encode(array(
            'TyLeTinhThue' => $TyLeTinhThue
        ));
        return $this->response;
    }
}