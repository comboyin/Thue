<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\dukienthuecuanamModel;
use Application\Forms\UploadForm;
use Quanlysothue\Models\dukienthuecuathangModel;
use Application\Entity\ketqua;
use Application\Models\nganhModel;
use Application\Entity\dukienthue;
use Quanlysothue\Froms\formDuKienThueCuaNam;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Unlity\Unlity;


class DukienthuecuathangController extends baseController
{

    public function indexAction()
    {
        $formUp = new UploadForm('upload-form');
        // Lấy năm gần nhất
        // to String 'Y'
        $today = (new \DateTime())->format('m/Y');
        
        $dukienthuecuanamModel = new dukienthuecuanamModel($this->getEntityManager());
        
        // danh sach theo nam
        $dsdkthuecuanam = $dukienthuecuanamModel->dsdukienthuecuanam($today, $this->getUser());
        
        return array(
            'formUp' => $formUp,
            'dsDuKienThueCuaThang' => $dsdkthuecuanam->getObj()
        );
        
        
        
    }
    
    
    //ajax
    public function dsdkcuanamDaDuyetAction(){
        
        $kythueNam = $this->getRequest()->getQuery()->get("KyThueSangSo");
        $kythueThang= $this->getRequest()->getQuery()->get("KyThueThang");
        $model = new dukienthuecuathangModel($this->getEntityManager());
        echo json_encode($model->dsDKthuecuanamDaDuyet($kythueNam,$kythueThang,$this->getUser()));
        
        return $this->response;
        
        
    }
    
    //ajax sang so
    public function sangsoAction(){
        $dsMaSoThue = $this->getRequest()->getPost()->get('dsMaSoThue');
        $dsTieuMuc = $this->getRequest()->getPost()->get('dsTieuMuc');
        $Nam = $this->getRequest()->getPost()->get('Nam');
        $Thang = $this->getRequest()->getPost()->get('Thang');
        
        $model = new dukienthuecuathangModel($this->getEntityManager());
        $kq = $model->SangSo($dsMaSoThue, $dsTieuMuc, $Nam, $Thang, $this->getUser());
        
        echo json_encode($kq->toArray());
        
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
            $dukienthuenam = new dukienthue();
            $form = new formDuKienThueCuaNam();
            $form->setData($post);
        
            // validation thanh cong
            if ($form->isValid()) {
                $dukienthuenamModel = new dukienthuecuanamModel($this->getEntityManager());
                /* @var $dukienthuenam dukienthue */
                $dukienthuenam = $dukienthuenamModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))->getObj();
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
                        
                        $dukienthuenam->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $NgayPhaiNop, 'Y-m-d'));
                        $dukienthuenam->setTrangThai(0);
                        $dukienthuenam->setUser($this->getUser());
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
}

?>