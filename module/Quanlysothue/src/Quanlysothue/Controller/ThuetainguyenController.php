<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\thuetainguyenModel;
use Quanlysothue\Models\dukienthuecuathangModel;
use Application\Entity\ketqua;
use Application\Entity\thue;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Unlity\Unlity;
use Quanlysothue\Froms\formDuKienThueCuaNam;
use Quanlysothue\Models\thuekhoanModel;

class ThuetainguyenController extends baseController
{

    public function indexAction()
    {
        $thuetainguyenModel = new thuetainguyenModel($this->getEntityManager());
        $temp = explode('/', (new \DateTime())->format('d/m/Y'));
        $Thang = $temp[1] . '/' . $temp[2];
        
        $dsThueTaiNguyen = $thuetainguyenModel->dsThueTaiNguyen($Thang, $this->getUser(), 'array')
            ->getObj();
        
        return array(
            'dsThueTaiNguyen' => $dsThueTaiNguyen
        );
    }

    public function dsThueTaiNguyenAction()
    {
        $Thang = $this->getRequest()
            ->getQuery()
            ->get('Thang');
        $model = new thuetainguyenModel($this->getEntityManager());
        echo json_encode($model->dsThueTaiNguyen($Thang, $this->getUser(), 'array')
            ->toArray());
        return $this->response;
    }

    public function DSDKThueTaiNguyenAction()
    {
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new dukienthuecuathangModel($this->getEntityManager());
        echo json_encode($model->DSDKThueTaiNguyenChuaGhiSo($Thang, $this->getUser(), 'array'));
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
        $model = new thuetainguyenModel($this->getEntityManager());
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
        $model = new thuetainguyenModel($this->getEntityManager());
        $kq = $model->duyet($dsMaSoThue, $dsTieuMuc, $Thang)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function xoaAction()
    {
        $model = new thuetainguyenModel($this->getEntityManager());
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
            $thuetainguyen = new thue();
            $form = new formDuKienThueCuaNam();
            $form->setData($post);
    
            // validation thanh cong
            if ($form->isValid()) {
                $thuetainguyenModel = new thuetainguyenModel($this->getEntityManager());
                $thuekhoanModel = new thuekhoanModel($this->getEntityManager());
                /* @var $thuekhoan thue */
                
                $thuetainguyen = $thuekhoanModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))->getObj();
                if ($thuetainguyen != null && $thuetainguyen->getTrangThai() == 0) {
    
                    $MaSoThue = $post->get('MaSoThue');
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                        // sua
    

    
                        $KyThue = $post->get('_KyThue');
                        $TieuMuc = $post->get('TieuMuc');
                        $ThueSuat = $post->get('ThueSuat');
                        $TenGoi = $post->get('TenGoi');
                        $SanLuong = $post->get('SanLuong');
                        $GiaTinhThue = $post->get('GiaTinhThue');
                        $NgayPhaiNop = $post->get('NgayPhaiNop');

                        if ($TieuMuc == '3801') // TN
                            $SoTien = intval($SanLuong * $GiaTinhThue * $ThueSuat);
                        else
                            $SoTien = $post->get('SoTien');
    
                        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                        $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
    
                        $thuetainguyen->setKyThue($KyThue);
                        $thuetainguyen->setNguoinopthue($nguoinopthue);
                        $thuetainguyen->setMuclucngansach($muclucngansach);
                        $thuetainguyen->setDoanhThuChiuThue(0);
                        $thuetainguyen->setTiLeTinhThue(0);
                        $thuetainguyen->setThueSuat($ThueSuat);
                        $thuetainguyen->setTenGoi($TenGoi);
                        $thuetainguyen->setSanLuong($SanLuong);
                        $thuetainguyen->setGiaTinhThue($GiaTinhThue);
                        $thuetainguyen->setSoTien($SoTien);
                        $thuetainguyen->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $NgayPhaiNop, 'Y-m-d'));
                        $thuetainguyen->setTrangThai(0);
                        $kq = $thuetainguyenModel->merge($thuetainguyen);
                    } else {
                        $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn hoặc đã nghĩ bỏ kinh doanh.";
                        $kq->setKq(false);
                        $kq->setMessenger($mss);
                    }
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger('Không tìm được thuế tài nguyên!');
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