<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\thuekhoanModel;
use Quanlysothue\Models\dukienthuecuathangModel;
use Application\Entity\ketqua;
use Application\Entity\thue;
use Quanlysothue\Froms\formDuKienThueCuaNam;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Models\nganhModel;
use Application\Unlity\Unlity;

class ThuekhoanController extends baseController
{

    public function indexAction()
    {
        $thuekhoanModel = new thuekhoanModel($this->getEntityManager());
        $temp = explode('/', (new \DateTime())->format('d/m/Y'));
        $Thang = $temp[1] . '/' . $temp[2];
        
        $dsThueKhoan = $thuekhoanModel->dsThueKhoan($Thang, $this->getUser(), 'array')
            ->getObj();
        
        return array(
            'dsThueKhoan' => $dsThueKhoan
        );
    }

    public function dsThueKhoanAction()
    {
        $Thang = $this->getRequest()
            ->getQuery()
            ->get('Thang');
        $model = new thuekhoanModel($this->getEntityManager());
        echo json_encode($model->dsThueKhoan($Thang, $this->getUser(), 'array')
            ->toArray());
        return $this->response;
    }

    public function DSDKThueKhoanAction()
    {
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new dukienthuecuathangModel($this->getEntityManager());
        echo json_encode($model->DSDKThueKhoanChuaGhiSo($Thang, $this->getUser(), 'array'));
        return $this->response;
    }

    public function ghisoAction()
    {
        $dsMaSoThue = $this->getRequest()
            ->getPost()
            ->get('dsMaSoThue');
        $dsTieuMuc = $this->getRequest()
            ->getPost()
            ->get('dsTieuMuc');
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new thuekhoanModel($this->getEntityManager());
        $kq = $model->ghiso($dsMaSoThue, $dsTieuMuc, $Thang)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function duyetAction()
    {
        $dsMaSoThue = $this->getRequest()
            ->getPost()
            ->get('dsMaSoThue');
        $dsTieuMuc = $this->getRequest()
            ->getPost()
            ->get('dsTieuMuc');
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new thuekhoanModel($this->getEntityManager());
        $kq = $model->duyet($dsMaSoThue, $dsTieuMuc, $Thang)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function xoaAction()
    {
        $model = new thuekhoanModel($this->getEntityManager());
        $kq= new ketqua();
        $MaSoThue = $this->getRequest()
            ->getPost()
            ->get('_MaSoThue');
        $KyThue = $this->getRequest()
            ->getPost()
            ->get('_KyThue');
        $TieuMuc = $this->getRequest()
            ->getPost()
            ->get('_TieuMuc');
        
        /* @var $thue thue */
        $thue = $this->em->find('Application\Entity\thue', array(
            'nguoinopthue' => $this->em->find('Application\Entity\nguoinopthue', $MaSoThue),
            'muclucngansach' => $this->em->find('Application\Entity\muclucngansach', $TieuMuc),
            'KyThue' => $KyThue
        ));
        
        if($thue==null){
            $kq->setKq(false);
            $kq->setMessenger('<span style="color : red;">Không tìm thấy thuế !</span>');
            echo json_encode($kq->toArray());
            return $this->response;
            
        }
        
        $kq = $model->remove($thue)->toArray();    
        
        echo json_encode($kq);
        return $this->response;
    }
    
    public function suaAction(){
    
        error_reporting(E_ERROR | E_PARSE);
    
        $kq = new ketqua();
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
            $post = $request->getPost();
            $thuekhoan = new thue();
            $form = new formDuKienThueCuaNam();
            $form->setData($post);
    
            // validation thanh cong
            if ($form->isValid()) {
                $thuekhoanModel = new thuekhoanModel($this->getEntityManager());
                /* @var $thuekhoan thue */
                $thuekhoan = $thuekhoanModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))->getObj();
                if ($thuekhoan != null && $thuekhoan->getTrangThai() == 0) {
    
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
                        $NgayPhaiNop = $post->get('NgayPhaiNop');
    
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
    
                        $thuekhoan->setKyThue($KyThue);
                        $thuekhoan->setNguoinopthue($nguoinopthue);
                        $thuekhoan->setMuclucngansach($muclucngansach);
                        $thuekhoan->setDoanhThuChiuThue($DoanhThuChiuThue);
                        $thuekhoan->setTiLeTinhThue($TiLeTinhThue);
                        $thuekhoan->setThueSuat($ThueSuat);
    
                        if ($TenGoi == "" && $TieuMuc != '2601' && $TieuMuc != '3801' && $TieuMuc != '1757')
                            $thuekhoan->setTenGoi(null);
                        else
                            $thuekhoan->setTenGoi($TenGoi);
    
                        if ($SanLuong == 0 && $TieuMuc != '2601' && $TieuMuc != '3801')
                            $thuekhoan->setSanLuong(null);
                        else
                            $thuekhoan->setSanLuong($SanLuong);
    
                        if ($GiaTinhThue == 0 && $TieuMuc != '2601' && $TieuMuc != '3801' && $TieuMuc != '1757')
                            $thuekhoan->setGiaTinhThue(null);
                        else
                            $thuekhoan->setGiaTinhThue($GiaTinhThue);
    
                        $thuekhoan->setSoTien($SoTien);
    
                        $thuekhoan->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $NgayPhaiNop, 'Y-m-d'));
                        $thuekhoan->setTrangThai(0);
                        $kq = $thuekhoanModel->merge($thuekhoan);
                    } else {
                        $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn hoặc đã nghĩ bỏ kinh doanh.";
                        $kq->setKq(false);
                        $kq->setMessenger($mss);
                    }
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger('Không tìm được thuế khoán');
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
}

?>