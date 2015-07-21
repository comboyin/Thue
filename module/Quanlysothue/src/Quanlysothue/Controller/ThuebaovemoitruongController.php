<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\thuebaovemoitruongModel;
use Quanlysothue\Models\dukienthuecuathangModel;
use Application\Entity\ketqua;
use Application\Entity\thue;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Unlity\Unlity;
use Quanlysothue\Froms\formDuKienThueCuaNam;

class ThuebaovemoitruongController extends baseController
{

    public function indexAction()
    {
        $thuebaovemoitruongModel = new thuebaovemoitruongModel($this->getEntityManager());
        $temp = explode('/', (new \DateTime())->format('d/m/Y'));
        $Thang = $temp[1] . '/' . $temp[2];
        
        $dsThueBaoVeMoiTruong = $thuebaovemoitruongModel->dsThueBaoVeMoiTruong($Thang, $this->getUser(), 'object')
            ->getObj();
        
        return array(
            'dsThueBaoVeMoiTruong' => $dsThueBaoVeMoiTruong
        );
    }

    public function dsThueBaoVeMoiTruongAction()
    {
        $Thang = $this->getRequest()
            ->getQuery()
            ->get('Thang');
        $model = new thuebaovemoitruongModel($this->getEntityManager());
        echo json_encode($model->dsThueBaoVeMoiTruong($Thang, $this->getUser(), 'array')
            ->toArray());
        return $this->response;
    }

    public function DSDKThueBaoVeMoiTruongAction()
    {
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new dukienthuecuathangModel($this->getEntityManager());
        echo json_encode($model->DSDKThueBaoVeMoiTruong($Thang, $this->getUser(), 'array'));
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
        $model = new thuebaovemoitruongModel($this->getEntityManager());
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
        $model = new thuebaovemoitruongModel($this->getEntityManager());
        $kq = $model->duyet($dsMaSoThue, $dsTieuMuc, $Thang)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function xoaAction()
    {
        $model = new thuebaovemoitruongModel($this->getEntityManager());
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
            $thuebaovemoitruong = new thue();
            $form = new formDuKienThueCuaNam();
            $form->setData($post);
    
            // validation thanh cong
            if ($form->isValid()) {
                $thuebaovemoitruongModel = new thuebaovemoitruongModel($this->getEntityManager());
                /* @var $thuekhoan thue */
                $thuebaovemoitruong = $thuebaovemoitruongModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))->getObj();
                if ($thuebaovemoitruong != null && $thuebaovemoitruong->getTrangThai() == 0) {
    
                    $MaSoThue = $post->get('MaSoThue');
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                        // sua
    

    
                        $KyThue = $post->get('_KyThue');
                        $TieuMuc = $post->get('TieuMuc');
                        //$ThueSuat = $post->get('ThueSuat');
                        $TenGoi = $post->get('TenGoi');
                        $SanLuong = $post->get('SanLuong');
                        $GiaTinhThue = $post->get('GiaTinhThue');
                        $NgayPhaiNop = $post->get('NgayPhaiNop');

                        if ($TieuMuc == '2601') // TN
                            $SoTien = intval($SanLuong * $GiaTinhThue);
                        else
                            $SoTien = $post->get('SoTien');
    
                        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                        $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
    
                        $thuebaovemoitruong->setKyThue($KyThue);
                        $thuebaovemoitruong->setNguoinopthue($nguoinopthue);
                        $thuebaovemoitruong->setMuclucngansach($muclucngansach);
                        $thuebaovemoitruong->setDoanhThuChiuThue(0);
                        $thuebaovemoitruong->setTiLeTinhThue(0);
                        $thuebaovemoitruong->setThueSuat(1);
                        $thuebaovemoitruong->setTenGoi($TenGoi);
                        $thuebaovemoitruong->setSanLuong($SanLuong);
                        $thuebaovemoitruong->setGiaTinhThue($GiaTinhThue);
                        $thuebaovemoitruong->setSoTien($SoTien);
                        $thuebaovemoitruong->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $NgayPhaiNop, 'Y-m-d'));
                        $thuebaovemoitruong->setTrangThai(0);
                        $kq = $thuebaovemoitruongModel->merge($thuebaovemoitruong);
                    } else {
                        $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn hoặc đã nghĩ bỏ kinh doanh.";
                        $kq->setKq(false);
                        $kq->setMessenger($mss);
                    }
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger('Không tìm được thuế bảo vệ môi trường!');
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