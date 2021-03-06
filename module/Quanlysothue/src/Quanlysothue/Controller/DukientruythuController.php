<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\dukientruythuModel;
use Application\Entity\dukientruythu;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Quanlysothue\Froms\formDuKienTruyThu;
use Application\Entity\ketqua;
use Zend\Http\Request;
use Zend\Form\Form;
use Quanlysothue\Froms\UploadForm;
use Quanlysothue\Excel\ImportExcelDuKienTruyThu;
use Quanlysothue\Models\dukienthuecuathangModel;

class DukientruythuController extends baseController
{

    /**
     * Hiển thị danh sách dự kiến truy thu theo kỳ thuế gần nhất.
     */
    public function indexAction()
    {
        $formUp = new UploadForm('upload-form');
        // Lấy kỳ thuế gần nhất
        // to String 'm-Y'
        $today = (new \DateTime())->format('m/Y');
        
        $model = new dukientruythuModel($this->getEntityManager());
        
        $dukientruythus = $model->dsDKTTJson($today, $this->getUser());
        
        
        return array(
            'formUp' => $formUp,
            'dsDuKienTruyThu' => $dukientruythus['obj']
        );
    }

    public function themAction()
    {
        //error_reporting(E_ERROR | E_PARSE);
        $kq = new ketqua();
        
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
            
            $dukientruythu = new dukientruythu();
            $form = new formDuKienTruyThu();
            $form->setInputFilter($dukientruythu->getInputFilter());
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
                    
                    $duKienThue = $this->getEntityManager()->find('Application\Entity\dukienthue', array(
                        'KyThue' => $KyThue,
                        'nguoinopthue' => $this->getEntityManager()
                            ->find('Application\Entity\nguoinopthue', $MaSoThue),
                        'muclucngansach' => $this->getEntityManager()
                            ->find('Application\Entity\muclucngansach', $TieuMuc)
                    ));
                    
                    $DoanhSo = $post->get('DoanhSo');
                    
                    $TrangThai = 0;
                    $LyDo = $post->get('LyDo');
                    $TiLeTinhThue = $post->get('TiLeTinhThue');
                    
                    $SoTien = $DoanhSo * $TiLeTinhThue;
                    $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
                    $muclucngansach = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
                    
                    $dukientruythu->setDukienthue($duKienThue);
                    $dukientruythu->setSoTien($SoTien);
                    $dukientruythu->setDoanhSo($DoanhSo);
                    $dukientruythu->setTrangThai($TrangThai);
                    $dukientruythu->setLyDo($LyDo);
                    $dukientruythu->setTiLeTinhThue($TiLeTinhThue);
                    $dukientruythu->setUser($this->getUser());
                    
                    $dukientruythuModel = new dukientruythuModel($this->getEntityManager());
                    $kq = $dukientruythuModel->them($dukientruythu);
                } else {
                    $mss = "Người nộp thuế này không thuộc quản lý của bạn hoặc đã nghĩ kinh doanh !";
                    $kq->setKq(false);
                    $kq->setMessenger($mss);
                }
            } else { // validation lỗi
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
            $mo = new dukientruythuModel($this->getEntityManager());
            /* @var $dukientruythu dukientruythu */
            
            $dukientruythu = $mo->findByID_($KyThue, $MaSoThue, $TieuMuc)->getObj();
            // kt ton tai
            if ($dukientruythu != null && $dukientruythu->getTrangThai() == 0) {
                // kiem tra masothue
                $kt = new nguoinopthueModel($this->getEntityManager());
                if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                    $kq->setKq(true);
                    $kq = $mo->remove($dukientruythu);
                } else {
                    $mss = "Người nộp thuế này không thuộc quản lý của bạn hoặc đã nghĩ kinh doanh !";
                    $kq->setKq(false);
                    $kq->setMessenger($mss);
                }
            } else {
                $kq->setKq(false);
                $kq->setMessenger('Không tìm được dự kiến truy thu !');
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
            $dukientruythu = new dukientruythu();
            $form = new formDuKienTruyThu();
            $form->setInputFilter($dukientruythu->getInputFilter());
            $form->setData($post);
            
            // validation thanh cong
            if ($form->isValid()) {
                
                // tim dukientruythu
                $dukientruythuModel = new dukientruythuModel($this->getEntityManager());
                /* @var $dukientruythu dukientruythu */
                $dukientruythu = $dukientruythuModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))
                    ->getObj();
                if ($dukientruythu != null && $dukientruythu->getTrangThai() == 0) {
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
                        
                        $dukientruythu->setNguoinopthue($nguoinopthue);
                        $dukientruythu->setMuclucngansach($muclucngansach);
                        $dukientruythu->setKyThue($KyThue);
                        $dukientruythu->setSoTien($SoTien);
                        $dukientruythu->setDoanhSo($DoanhSo);
                        $dukientruythu->setTrangThai($TrangThai);
                        $dukientruythu->setLyDo($LyDo);
                        $dukientruythu->setTiLeTinhThue($TiLeTinhThue);
                        $dukientruythu->setUser($this->getUser());
                        
                        $kq = $dukientruythuModel->merge($dukientruythu);
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
            $model = new dukientruythuModel($this->getEntityManager());
            $dem = 0;
            
            for ($i = 0; $i < count($MaSoThueData); $i ++) {
                
                $dukientruythu = $model->findByID_($KyThue, $MaSoThueData[$i], $TieuMucData[$i])->getObj();
                if ($dukientruythu != null && $dukientruythu->getTrangThai() == 0) {
                    // kiem tra nguoi nop thue co thuoc quyen quan ly cua cbt do khong ?
                    $kt = new nguoinopthueModel($this->getEntityManager());
                    if ($kt->ktNNT($MaSoThueData[$i], $this->getUser()) == true) {
                        $model->remove($dukientruythu);
                        $dem ++;
                    } else {
                        $mss = "Người nộp thuế có mã " . $MaSoThueData[$i] . " không thuộc quyền quản lý của bạn hoặc đã nghĩ kinh doanh!";
                        $kq->setKq(false);
                        $kq->setMessenger($mss);
                        $this->getEntityManager()
                            ->getConnection()
                            ->rollBack();
                        echo json_encode($kq->toArray());
                        return $this->response;
                    }
                } else {
                    $mss = "Không tìm được dự kiến truy thu có mã " . $MaSoThueData[$i] . " !";
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
     * Trả về danh sách dự kiến truy thu
     */
    public function dsDKTTJsonAction()
    {
        $kq = new ketqua();
        try {
            $get = $this->getRequest()->getQuery();
            
            $model = new dukientruythuModel($this->getEntityManager());
            
            $dukientruythus = $model->dsDKTTJson($get->get('KyThue'), $this->getUser());
            
            echo json_encode($dukientruythus);
            return $this->response;
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            echo json_encode($kq->toArray());
            return $this->response;
        }
    }
/**
 * import dữ liệu bằng file 
 * @return \Zend\Mvc\Controller\Response  */
    public function uploadFormAction()
    {
        $form = new UploadForm('upload-form');
        
        $request = $this->getRequest();
        $tempFile = null;
        if ($request->isPost()) {
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            
            // var_dump($post);
            
            $form->setData($post);
            $array = [];
            
            if ($form->isValid()) {
                $data = $form->getData();
                $fileName = $data['dukientruythu-file']['tmp_name'];
                $model = new \Application\Models\dukientruythuModel($this->getEntityManager());
                
                $ImportData = new ImportExcelDuKienTruyThu();
                
                // validation file
                $fileNameErr = $ImportData->CheckFileImport($fileName, $this->getEntityManager(), $this->getUser());
                
                // nếu lỗi
                if ($fileNameErr->getKq() == false) {
                    
                        // file sai ràng buộc database
                    if ($fileNameErr->getObj() != null && file_exists($fileNameErr->getObj())) {
                        echo json_encode(array(
                            'sucess' => false,
                            'mess' => $fileNameErr->getMessenger(),
                            'fileNameErr' => $fileNameErr->getObj()
                        ));
                    } else {
                        // File sai dinh dang
                        echo json_encode(array(
                            'sucess' => false,
                            'mess' => $fileNameErr->getMessenger()
                        ));
                    }
                    
                    
                    
                } else {

                    // Form is valid, save the form!
                    // var_dump($data);
                    
                    $kq = $ImportData->PersitToArrayCollection($fileName, $this->getUser(),$this->getEntityManager());
                    
                    $array['sucess'] = $kq->getKq();
                    $array['mess'] = $kq->getMessenger();
                    
                    $array['KyThue']  = $kq->getObj();
                
                    echo json_encode($array);
                    
                }
                
                
               
            }
        }
        if(file_exists($fileName)){
            unlink($fileName);
        }
        return $this->response;
    }

    /**
     * Tra ve danh sach du kien thue (1003,1701) khong co truy thu
     * 
     * @return \Zend\Mvc\Controller\Response
     */
    public function DSDuKienThueNotTruyThuAction()
    {
        $KyThue = $this->getRequest()
            ->getPost()
            ->get('KyThue');
        $model = new dukienthuecuathangModel($this->getEntityManager());
        $kq = $model->DSDuKienThueNotTruyThu($KyThue, $this->getUser(), 'array');
        echo json_encode($kq['obj']);
        return $this->response;
    }
}
