<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\thuetruythuModel;
use Quanlysothue\Models\dukientruythuModel;
use Quanlysothue\Froms\formDuKienTruyThu;
use Application\Entity\ketqua;
use Application\Entity\truythu;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class ThuetruythuController extends baseController
{

    public function indexAction()
    {
        $thuetruythuModel = new thuetruythuModel($this->getEntityManager());
        $temp = explode('/', (new \DateTime())->format('d/m/Y'));
        $Thang = $temp[1] . '/' . $temp[2];
        
        $dsThueTruyThu = $thuetruythuModel->dsThueTruyThu($Thang, $this->getUser(), 'object')
            ->getObj();
        
        return array(
            'dsThueTruyThu' => $dsThueTruyThu
        );
    }

    public function dsThueTruyThuAction()
    {
        $Thang = $this->getRequest()
            ->getQuery()
            ->get('Thang');
        $model = new thuetruythuModel($this->getEntityManager());
        echo json_encode($model->dsThueTruyThu($Thang, $this->getUser(), 'array')
            ->toArray());
        return $this->response;
    }

    public function DSDKThueTruyThuAction()
    {
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new dukientruythuModel($this->getEntityManager());
        echo json_encode($model->DSDKThueTruyThu($Thang, $this->getUser(), 'array'));
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
        $model = new thuetruythuModel($this->getEntityManager());
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
        $model = new thuetruythuModel($this->getEntityManager());
        $kq = $model->duyet($dsMaSoThue, $dsTieuMuc, $Thang)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function xoaAction()
    {
        $model = new thuetruythuModel($this->getEntityManager());
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
        
        /* @var $truythu truythu */
        $truythu = $this->em->find('Application\Entity\truythu', array(
            'nguoinopthue' => $this->em->find('Application\Entity\nguoinopthue', $MaSoThue),
            'muclucngansach' => $this->em->find('Application\Entity\muclucngansach', $TieuMuc),
            'KyThue' => $KyThue
        ));
        
        if($truythu==null){
            $kq->setKq(false);
            $kq->setMessenger('<span style="color : red;">Không tìm thấy thuế !</span>');
            echo json_encode($kq->toArray());
            return $this->response;
            
        }
        
        $kq = $model->remove($truythu)->toArray();    
        
        echo json_encode($kq);
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
            $truythu = new truythu();
            $form = new formDuKienTruyThu();
            $form->setInputFilter($truythu->getInputFilter());
            $form->setData($post);
    
            // validation thanh cong
            if ($form->isValid()) {
    
                // tim dukientruythu
                $thuetruythuModel = new thuetruythuModel($this->getEntityManager());
                /* @var $dukientruythu dukientruythu */
                $truythu = $thuetruythuModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))
                ->getObj();
                if ($truythu != null && $truythu->getTrangThai() == 0) {
                    $MaSoThue = $post->get('MaSoThue');
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                        // sua
                        $KyThue = $post->get('_KyThue');
    
                        $TieuMuc = $post->get('TieuMuc');
                        $SoTien = $post->get('SoTien');
                        $TrangThai = 0;
                        $LyDo = $post->get('LyDo');
                        $TiLeTinhThue = $post->get('TiLeTinhThue');
                        $DoanhSo = $post->get('DoanhSo');
    
                        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                        $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
    
                        $truythu->setNguoinopthue($nguoinopthue);
                        $truythu->setMuclucngansach($muclucngansach);
                        $truythu->setKyThue($KyThue);
                        $truythu->setSoTien($SoTien);
                        $truythu->setDoanhSo($DoanhSo);
                        $truythu->setTrangThai($TrangThai);
                        $truythu->setLyDo($LyDo);
                        $truythu->setTiLeTinhThue($TiLeTinhThue);
    
                        $kq = $thuetruythuModel->merge($truythu);
                    } else {
                        $mss = "Người nộp thuế này không thuộc quản lý của bạn hoặc đã nghĩ kinh doanh !";
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
    
        // trả về json
        echo json_encode($kq->toArray());
        return $this->response;
    }
}

?>