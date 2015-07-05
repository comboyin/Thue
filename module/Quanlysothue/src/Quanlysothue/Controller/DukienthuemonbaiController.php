<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\dukienthuemonbaiModel;
use Quanlysothue\Froms\formDuKienThueMonBai;
use Application\Entity\ketqua;
use Zend\Http\Request;
use Zend\Form\Form;
use Quanlysothue\Froms\UploadForm;
use Quanlysothue\Excel\ImportExcelDuKienThueCuaNam;
use Application\Entity\dukienmb;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\muclucngansach;
use Application\Entity\nguoinopthue;
use Application\Unlity\Unlity;

class DukienthuemonbaiController extends baseController
{

    public function indexAction()
    {
        $formUp = new UploadForm('upload-form');
        // Lấy năm gần nhất
        // to String 'Y'
        $today = (new \DateTime())->format('Y');
        
        $model = new dukienthuemonbaiModel($this->getEntityManager());
        
        // danh sach theo nam
        $dsdkthuemb = $model->dsdukienthuemonbai($today, $this->getUser());
        
        return array(
            'formUp' => $formUp,
            'dsDuKienThueMonBai' => $dsdkthuemb->getObj()
        );
    }

    public function themAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        $kq = new ketqua();
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
            
            $dukienthuemb = new dukienmb();
            $model = new dukienthuemonbaiModel($this->getEntityManager());
            $form = new formDuKienThueMonBai();
            $form->setData($request->getPost());
            
            // validation thanh cong
            if ($form->isValid()) {
                $post = $request->getPost();
                $MaSoThue = $post->get('MaSoThue');
                
                $kt = new nguoinopthueModel($this->getEntityManager());
                if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                    
                    // them
                    $KyThue = $post->get('KyThue');
                    $TieuMuc = $post->get('TieuMuc');
                    $NgayPhaiNop = $post->get('NgayPhaiNop');
                    
                    /* @var $nguoinopthue nguoinopthue */
                    /* @var $muclucngansach muclucngansach */
                    $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                    $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
                    
                    // kt nnt co phai lan dau dc lap du kien thue ko?
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
                    
                    $dukienthuemb->setNam($KyThue);
                    $dukienthuemb->setNguoinopthue($nguoinopthue);
                    $dukienthuemb->setMuclucngansach($muclucngansach);
                    $dukienthuemb->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $NgayPhaiNop, 'Y-m-d'));
                    $dukienthuemb->setSoTien($SoTien);
                    $dukienthuemb->setTrangThai(0);
                    
                    $kq = $model->them($dukienthuemb);
                } else {
                    $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn hoặc đã nghĩ bỏ kinh doanh không thể sửa dự kiến thuế.";
                    $kq->setKq(false);
                    $kq->setMessenger($mss);
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

    public function xoaAction()
    {
        $kq = new ketqua();
        try {
            $request = $this->getRequest();
            $post = $request->getPost();
            $MaSoThue = $post->get('_MaSoThue');
            $KyThue = $post->get('_KyThue');
    
            // xoa trong csdl
            $model = new dukienthuemonbaiModel($this->getEntityManager());
            $dukienthuemb = $model->findByID_($KyThue, $MaSoThue)->getObj();
            //kt ton tai
            if ($dukienthuemb != null && $dukienthuemb->getTrangThai() == 0) {
                // kiem tra masothue
                $kt = new nguoinopthueModel($this->getEntityManager());
                if($kt->ktNNT($MaSoThue, $this->getUser()) == true)
                {
                    $kq->setKq(true);
                    $kq = $model->remove($dukienthuemb);
                    
                } else {
                    $mss = "Người nộp thuế này không thuộc quyền quản lý của bạn.";
                    $kq->setKq(false);
                    $kq->setMessenger($mss);
                }     
            } else {
                $kq->setKq(false);
                $kq->setMessenger('Không tìm được dự kiến thuế môn bài !');
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
            $dukienthuemb = new dukienmb();
            $model = new dukienthuemonbaiModel($this->getEntityManager());
            $form = new formDuKienThueMonBai();
            $form->setData($post);
            
            // validation thanh cong
            if ($form->isValid()) {
                
                
                $dukienthuemb = $model->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'))
                ->getObj();
                if ($dukienthuemb != null) {
                
                    $MaSoThue = $post->get('MaSoThue');
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                        
                        // sua
                        $KyThue = $post->get('_KyThue');
                        $TieuMuc = $post->get('TieuMuc');
                        $NgayPhaiNop = $post->get('NgayPhaiNop');
                        
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
                        
                        $dukienthuemb->setNam($KyThue);
                        $dukienthuemb->setNguoinopthue($nguoinopthue);
                        $dukienthuemb->setMuclucngansach($muclucngansach);
                        $dukienthuemb->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $NgayPhaiNop, 'Y-m-d'));
                        $dukienthuemb->setSoTien($SoTien);
                        $dukienthuemb->setTrangThai(0);
                        
                        $kq = $model->merge($dukienthuemb);
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
            $model = new dukienthuemonbaiModel($this->getEntityManager());
            $dem = 0;
            
            
            for ($i = 0; $i < count($MaSoThueData); $i ++) {
                
                $dukienthuemb = $model->findByID_($KyThue, $MaSoThueData[$i])->getObj();
                if ($dukienthuemb != null && $dukienthuemb->getTrangThai() == 0) {
                    // kiem tra nguoi nop thue co thuoc quyen quan ly cua cbt do khong ?
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThueData[$i], $this->getUser()) == true) 
                    {
                        $model->remove($dukienthuemb);
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
                    $mss = "Không tìm được dự kiến môn bài có mã ".$MaSoThueData[$i]." !";
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
     * Trả về danh sách dự kiến thuế môn bài
     */
    public function dsDKTMBJsonAction()
    {
        $kq = new ketqua();
        try {
            $get = $this->getRequest()->getQuery();
            
            $model = new dukienthuemonbaiModel($this->getEntityManager());
            
            // danh sach theo nam
            $dsdkthuemb = $model->dsDKTMBJson($get->get('KyThue'), $this->getUser());
            
            echo json_encode($dsdkthuemb);
            return $this->response;
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            echo json_encode($kq->toArray());
            return $this->response;
        }
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