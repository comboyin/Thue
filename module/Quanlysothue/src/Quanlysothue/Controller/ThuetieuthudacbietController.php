<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\dukienthuecuathangModel;
use Application\Entity\ketqua;
use Application\Entity\thue;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Unlity\Unlity;
use Quanlysothue\Froms\formDuKienThueCuaNam;
use Quanlysothue\Models\thuetieuthudacbietModel;
use Quanlysothue\Models\thuekhoanModel;

class ThuetieuthudacbietController extends baseController
{

    public function indexAction()
    {
        $thuetieuthudacbietModel = new thuetieuthudacbietModel($this->getEntityManager());
        $temp = explode('/', (new \DateTime())->format('d/m/Y'));
        $Thang = $temp[1] . '/' . $temp[2];
        
        $dsThueTieuThuDacBiet = $thuetieuthudacbietModel->dsThueTieuThuDacBiet($Thang, $this->getUser(), 'array')
            ->getObj();
        
        return array(
            'dsThueTieuThuDacBiet' => $dsThueTieuThuDacBiet
        );
    }

    public function dsThueTieuThuDacBietAction()
    {
        $Thang = $this->getRequest()
            ->getQuery()
            ->get('Thang');
        $model = new thuetieuthudacbietModel($this->getEntityManager());
        echo json_encode($model->dsThueTieuThuDacBiet($Thang, $this->getUser(), 'array')
            ->toArray());
        return $this->response;
    }

    public function DSDKThueTieuThuDacBietAction()
    {
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new dukienthuecuathangModel($this->getEntityManager());
        echo json_encode($model->DSDKThueTieuThuDacBietChuaGhiSo($Thang, $this->getUser(), 'array'));
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
        $model = new thuetieuthudacbietModel($this->getEntityManager());
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
        $model = new thuetieuthudacbietModel($this->getEntityManager());
        $kq = $model->duyet($dsMaSoThue, $dsTieuMuc, $Thang)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function xoaAction()
    {
        $model = new thuetieuthudacbietModel($this->getEntityManager());
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
            $thue = new thue();
            $form = new formDuKienThueCuaNam();
            $form->setData($post);
    
            // validation thanh cong
            if ($form->isValid()) {
                $thuetieuthudacbietModel = new thuetieuthudacbietModel($this->getEntityManager());
                $thuekhoanModel = new thuekhoanModel($this->getEntityManager());
                /* @var $thuekhoan thue */
                
                $thue = $thuekhoanModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))->getObj();
                if ($thue != null && $thue->getTrangThai() == 0) {
    
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
    
                        $thue->setKyThue($KyThue);
                        $thue->setNguoinopthue($nguoinopthue);
                        $thue->setMuclucngansach($muclucngansach);
                        $thue->setDoanhThuChiuThue(0);
                        $thue->setTiLeTinhThue(0);
                        $thue->setThueSuat($ThueSuat);
                        $thue->setTenGoi($TenGoi);
                        $thue->setSanLuong($SanLuong);
                        $thue->setGiaTinhThue($GiaTinhThue);
                        $thue->setSoTien($SoTien);
                        $thue->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $NgayPhaiNop, 'Y-m-d'));
                        $thue->setTrangThai(0);
                        $kq = $thuetieuthudacbietModel->merge($thue);
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