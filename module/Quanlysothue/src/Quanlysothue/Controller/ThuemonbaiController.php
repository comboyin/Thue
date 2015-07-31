<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\thuemonbaiModel;
use Quanlysothue\Models\dukienthuemonbaiModel;
use Application\Entity\ketqua;
use Application\Entity\thuemonbai;
use Quanlysothue\Froms\formDuKienThueMonBai;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Unlity\Unlity;

class ThuemonbaiController extends baseController
{

    public function indexAction()
    {
        $thuemonbaiModel = new thuemonbaiModel($this->getEntityManager());
        $Nam = (new \DateTime())->format('Y');
        
        $dsThueMonBai = $thuemonbaiModel->dsThueMonBai($Nam, $this->getUser(), 'object')
            ->getObj();
        
        return array(
            'dsThueMonBai' => $dsThueMonBai
        );
    }

    public function dsThueMonBaiAction()
    {
        $Nam = $this->getRequest()
            ->getQuery()
            ->get('Nam');
        $model = new thuemonbaiModel($this->getEntityManager());
        echo json_encode($model->dsThueMonBai($Nam, $this->getUser(), 'array')
            ->toArray());
        return $this->response;
    }

    public function DSDKThueMonBaiAction()
    {
        $Nam = $this->getRequest()
            ->getPost()
            ->get('Nam');
        $model = new dukienthuemonbaiModel($this->getEntityManager());
        echo json_encode($model->DSDKThueMonBai($Nam, $this->getUser(), 'array'));
        return $this->response;
    }

    public function ghisoAction()
    {
        $dsMaSoThue = $this->getRequest()
            ->getPost()
            ->get('dsMaSoThue');
        $Nam = $this->getRequest()
            ->getPost()
            ->get('Nam');
        $model = new thuemonbaiModel($this->getEntityManager());
        $kq = $model->ghiso($dsMaSoThue, $Nam)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function duyetAction()
    {
        $dsMaSoThue = $this->getRequest()
            ->getPost()
            ->get('dsMaSoThue');
        $Nam = $this->getRequest()
            ->getPost()
            ->get('Nam');
        $model = new thuemonbaiModel($this->getEntityManager());
        $kq = $model->duyet($dsMaSoThue, $Nam)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function xoaAction()
    {
        $model = new thuemonbaiModel($this->getEntityManager());
        $kq= new ketqua();
        $MaSoThue = $this->getRequest()
            ->getPost()
            ->get('_MaSoThue');
        $KyThue = $this->getRequest()
            ->getPost()
            ->get('_KyThue');
        
        /* @var $thuemonbai thuemonbai */
        $thuemonbai = $this->em->find('Application\Entity\thuemonbai', array(
            'nguoinopthue' => $this->em->find('Application\Entity\nguoinopthue', $MaSoThue),
            //'muclucngansach' => $this->em->find('Application\Entity\muclucngansach', $TieuMuc),
            'KyThue' => $KyThue
        ));
        
        if($thuemonbai==null){
            $kq->setKq(false);
            $kq->setMessenger('<span style="color : red;">Không tìm thấy thuế !</span>');
            echo json_encode($kq->toArray());
            return $this->response;
            
        }
        
        $kq = $model->remove($thuemonbai)->toArray();    
        
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
            $thuemonbai = new thuemonbai();
            $model = new dukienthuemonbaiModel($this->getEntityManager());
            $form = new formDuKienThueMonBai();
            $form->setData($post);
    
            // validation thanh cong
            if ($form->isValid()) {
                $thuemonbaiModel = new thuemonbaiModel($this->getEntityManager());
                /* @var $thuemonbai thuemonbai */
                $thuemonbai = $thuemonbaiModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'))->getObj();
                if ($thuemonbai != null && $thuemonbai->getTrangThai() == 0) {
    
                    $MaSoThue = $post->get('MaSoThue');
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                        // sua
                        $KyThue = $post->get('_KyThue');
                        $TieuMuc = $post->get('TieuMuc');
                        $NgayPhaiNop = $post->get('NgayPhaiNop');
                        $DoanhSo = $post->get('DoanhSo');
                        
                        /* @var $nguoinopthue nguoinopthue */
                        /* @var $muclucngansach muclucngansach */
                        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                        $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
                        
                        // kt nnt co phai lan dau dc lap du kien thue ko?
                        if ($model->ktMBUp($MaSoThue) == true) {
                            $dateCur = $nguoinopthue->getThoiDiemBDKD();
                            $year = Unlity::stringDateToStringYear($dateCur);
                            $dateBegin = '01-01-' . $year;
                            $dateBegin = '31-06-' . $year;
                            $ktngay = Unlity::CheckDateBetweenTowDate($dateBegin, $dateBegin, $dateCur);
                            if ($ktngay == true) {
                                $SoTien = $muclucngansach->getSoTien();
                            } else {
                                $SoTien = intval($muclucngansach->getSoTien()) / 2;
                            }
                        } else {
                            $SoTien = $muclucngansach->getSoTien();
                        }
                        
                        $thuemonbai->setNam($KyThue);
                        $thuemonbai->setNguoinopthue($nguoinopthue);
                        $thuemonbai->setMuclucngansach($muclucngansach);
                        $thuemonbai->setDoanhSo($DoanhSo);
                        $thuemonbai->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $NgayPhaiNop, 'Y-m-d'));
                        $thuemonbai->setSoTien($SoTien);
                        $thuemonbai->setTrangThai(0);
                        $thuemonbai->setUser($this->getUser());
                        
                        $kq = $thuemonbaiModel->merge($thuemonbai);
                    } else {
                        $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn hoặc đã nghĩ bỏ kinh doanh.";
                        $kq->setKq(false);
                        $kq->setMessenger($mss);
                    }
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger('Không tìm được thuế môn bài');
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
    
    public function loadSoTienAction()
    {
        $MaSoThue = $this->getRequest()
        ->getQuery()
        ->get('MaSoThue');
        $TieuMuc = $this->getRequest()
        ->getQuery()
        ->get('TieuMuc');
        $flag = $this->getRequest()
        ->getQuery()
        ->get('flag');
    
        $model = new dukienthuemonbaiModel($this->getEntityManager());
        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
        $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
    
        if($flag == "new")
        {
            if ($model->ktMBIn($MaSoThue) == true) {
                $dateCur = $nguoinopthue->getThoiDiemBDKD();
                $year = Unlity::stringDateToStringYear($dateCur);
                $dateBegin = '01-01-' . $year;
                $dateBegin = '31-06-' . $year;
                $ktngay = Unlity::CheckDateBetweenTowDate($dateBegin, $dateBegin, $dateCur);
                if ($ktngay == true) {
                    $SoTien = $muclucngansach->getSoTien();
                } else {
                    $SoTien = intval($muclucngansach->getSoTien()) / 2;
                }
            } else {
                $SoTien = $muclucngansach->getSoTien();
            }
        } else if($flag == "edit")
        {
            if ($model->ktMBUp($MaSoThue) == true) {
                $dateCur = $nguoinopthue->getThoiDiemBDKD();
                $year = Unlity::stringDateToStringYear($dateCur);
                $dateBegin = '01-01-' . $year;
                $dateBegin = '31-06-' . $year;
                $ktngay = Unlity::CheckDateBetweenTowDate($dateBegin, $dateBegin, $dateCur);
                if ($ktngay == true) {
                    $SoTien = $muclucngansach->getSoTien();
                } else {
                    $SoTien = intval($muclucngansach->getSoTien()) / 2;
                }
            } else {
                $SoTien = $muclucngansach->getSoTien();
            }
        } else
            $SoTien = 0;
        echo json_encode(array(
            'SoTien' => $SoTien
        ));
        return $this->response;
    }
    

}

?>