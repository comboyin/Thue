<?php
namespace Quanlynguoinopthue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Form\FormMienGiam;
use Application\Models\miengiamModel;
use Application\Entity\miengiamthue;
use Application\Entity\ketqua;
use Quanlynguoinopthue\Form\FormCTMienGiam;
use Application\Entity\kythuemg;
use Application\Forms\UploadForm;
use Quanlynguoinopthue\Excel\ImportExcelMienGiam;
use Application\Unlity\Unlity;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class MiengiamController extends baseController
{

    public function indexAction()
    {
        $formUp = new UploadForm('upload-form');
        
        return array(
            'formUp' => $formUp
        );
    }

    public function uploadFormAction()
    {
        $form = new UploadForm();
        
        $request = $this->getRequest();
        $tempFile = null;
        if ($request->isPost()) {
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            
            // var_dump($post);
            
            $form->setData($post);
            
            if ($form->isValid()) {
                $data = $form->getData();
                $fileName = $data['file-excel']['tmp_name'];
                
                $ImportData = new ImportExcelMienGiam();
                
                // validation file
                $fileNameErr = false; /* $ImportData->CheckFileImport($fileName, $this->getEntityManager()); */
                // nếu lỗi
                if ($fileNameErr !== false) {
                    echo json_encode(array(
                        'sucess' => false,
                        'mess' => 'File bạn sử dụng gặp một số vấn đề, chúng tôi gởi cho lại cho bạn file với các đánh dấu lỗi, vui lòng kiểm tra và thử lại !',
                        'fileNameErr' => $fileNameErr
                    ));
                    unlink($fileName);
                    return $this->response;
                } else {
                    $kq = $ImportData->PersitToDatabase($fileName, $this->getEntityManager());
                    // xóa file
                    unlink($fileName);
                    // Form is valid, save the form!
                    // var_dump($data);
                    if ($kq->getKq() == true) {
                        echo json_encode(array(
                            'sucess' => true,
                            'mess' => 'Import thành công !'
                        ));
                    } else {
                        echo json_encode(array(
                            'sucess' => false,
                            'mess' => $kq->getMessenger()
                        ));
                    }
                    
                    return $this->response;
                }
            }
        }
        return $this->response;
    }

    public function themkythuemgAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        /* @var $request Request */
        /* @var $form Form */
        $request = $this->getRequest();
        $kq = new ketqua();
        $form = new FormCTMienGiam();
        $form->setData($request->getPost());
        
        // validation thanh cong
        if ($form->isValid()) {
            $miengiamModel = new miengiamModel($this->getEntityManager());
            $post = $request->getPost();
            
            // them
            $KyThue = $post->get('KyThue');
            $TieuMuc = $post->get('TieuMuc');
            $SoTien = $post->get('SoTien');
            $SoQDMG = $post->get('SoQDMG');
            if($this->checkSoQD($SoQDMG, $kq)==false){
                echo json_encode($kq->toArray());
                return $this->response;
            }
            //$NgayHachToan = $post->get('NgayHachToan');
            
            $ChiTietMg = new kythuemg();
            $ChiTietMg->setKyThue($KyThue);
            $ChiTietMg->setMuclucngansach($this->getEntityManager()
                ->find('Application\Entity\muclucngansach', $TieuMuc));
            $ChiTietMg->setSoTien($SoTien);
            $ChiTietMg->setChungtu($this->getEntityManager()
                ->find('Application\Entity\chungtu', $SoQDMG));
            
            //$ChiTietCt->setNgayHachToan(Unlity::ConverDate('d-m-Y', $NgayHachToan, 'Y-m-d'));
            
            $kq = $miengiamModel->them($ChiTietMg);
        } else { // validation lỗi
            $mss = $this->getErrorMessengerForm($form);
            $kq->setKq(false);
            $kq->setMessenger($mss);
        }
        
        // trả về json
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function xoaAction()
    {
        try {
            $kq = new ketqua();
            $this->getEntityManager()
                ->getConnection()
                ->beginTransaction();
            $request = $this->getRequest();
            $post = $request->getPost();
            $SoQDMG = $post->get('SoQDMG');
            
            if($this->checkSoQD($SoQDMG,$kq)==false){
                echo json_encode($kq->toArray());
                return $this->response;
            }
            
            
            $miengiamModel = new miengiamModel($this->getEntityManager());
            $MienGiam = $this->getEntityManager()->find('Application\Entity\miengiamthue', $SoQDMG);
            
            $kq = $miengiamModel->remove($MienGiam);
            
            $this->getEntityManager()
                ->getConnection()
                ->commit();
        } catch (\Exception $e) {
            
            $this->getEntityManager()
                ->getConnection()
                ->rollBack();
        }
        
        echo json_encode($kq->toArray());
        
        return $this->response;
    }

    public function xoaCTMienGiamAction()
    {
        $kq = new ketqua();
        $SoQDMG = $this->getRequest()
            ->getPost()
            ->get("SoQDMG");
        $KyThue = $this->getRequest()
            ->getPost()
            ->get("KyThue");
        $TieuMuc = $this->getRequest()
            ->getPost()
            ->get("TieuMuc");
        if($this->checkSoQD($SoQDMG, $kq)==false){
            echo json_encode($kq->toArray());
            return $this->response;
        }
        $MucLuc = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
        $MienGiam = $this->getEntityManager()->find('Application\Entity\miengiamthue', $SoQDMG);
        
        $kythuemg = $this->getEntityManager()->find('Application\Entity\kythuemg', array(
            'KyThue' => $KyThue,
            'miengiamthue' => $MienGiam,
            'muclucngansach' => $MucLuc
        ));
        
        if ($kythuemg != null) {
            $model = new miengiamModel($this->getEntityManager());
            $kq = $model->remove($kythuemg);
        } else {
            $kq->setKq(false);
            $kq->setMessenger('Không tìm thấy Chi Tiết Miễn Giảm này !');
        }
        
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function suaAction()
    {
        // error_reporting(0);
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
            $post = $request->getPost();
            $kq = new ketqua();
            $form = new FormMienGiam();
            
            $form->setData($request->getPost());
            
            // validation thanh cong
            if ($form->isValid()) {
                $MaSoThue = $post->get('MaSoThue');
                $SoQDMG = $post->get('NgayChungTu');
                if($this->checkMaSoThue($MaSoThue, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                
                
                if($this->checkSoQD($SoQDMG, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                
                /* @var $ChungTu chungtu */
                $ChungTu = $this->getEntityManager()->find('Application\Entity\chungtu', $post->get('_SoQDMG'));
                
                if ($ChungTu != null) {
                    
                    $miengiamModel = new miengiamModel($this->getEntityManager());
                    $ChungTu->setSoQDMG($post->get('SoQDMG'));
                    $ChungTu->setNgayChungTu(Unlity::ConverDate('m-d-Y', $post->get('NgayChungTu'), 'Y-m-d'));
                    $ChungTu->setNguoinopthue($this->getEntityManager()
                        ->find('Application\Entity\nguoinopthue', $post->get('MaSoThue')));
                    $kq = $miengiamModel->merge($ChungTu);
                } else {
                    
                    $kq->setKq(false);
                    $kq->appentMessenger("Sô chứng từ không tồn tại !");
                }
            }             

            // validation lỗi
            else {
                
                $kq->setKq(false);
                $kq->appentMessenger($this->getErrorMessengerForm($form));
            }
            
            // trả về json
            echo json_encode($kq->toArray());
            return $this->response;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            echo json_encode($kq->toArray());
        }
    }

    public function themAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        /* @var $request Request */
        /* @var $form Form */
        $request = $this->getRequest();
        $kq = new ketqua();
        
        $form = new FormMienGiam();
        
        $form->setData($request->getPost());
        
        // validation thanh cong
        if ($form->isValid()) {
            $miengiamModel = new miengiamModel($this->getEntityManager());
            $post = $request->getPost();
            
            // them
            $NgayChungTu = $post->get('NgayChungTu');
            $SoQDMG = $post->get('SoQDMG');
            $MaSoThue = $post->get('MaSoThue');
            
            $kt = new nguoinopthueModel($this->getEntityManager());
            if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                $chungtu = new chungtu();
                $chungtu->setSoQDMG($SoQDMG);
                $chungtu->setNgayChungTu(Unlity::ConverDate('d-m-Y', $NgayChungTu, 'Y-m-d'));
                $chungtu->setNguoinopthue($this->getEntityManager()
                    ->find('Application\Entity\nguoinopthue', $MaSoThue));
                
                $kq = $miengiamModel->them($chungtu);
            } else {
                $kq->setKq(false);
                $kq->setMessenger("Mã số thuế $MaSoThue không thuộc quản lý của bạn !");
            }
        } else { // validation lỗi
            $mss = $this->getErrorMessengerForm($form);
            $kq->setKq(false);
            $kq->setMessenger($kq->getMessenger() . "\n" . $mss);
        }
        
        // trả về json
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function suaCTChungTuAction()
    {
        // error_reporting(0);
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
            $post = $request->getPost();
            $kq = new ketqua();
            $form = new FormCTMienGiam();
            
            $form->setData($request->getPost());
            
            // validation thanh cong
            if ($form->isValid()) {
                $_KyThue = $post->get('_KyThue');
                $_TieuMuc = $post->get('_TieuMuc');
                
                $KyThue = $post->get('KyThue');
                $SoQDMG = $post->get('SoQDMG');
                if($this->checkSoQD($SoQDMG, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                $TieuMuc = $post->get('TieuMuc');
                //$NgayHachToan = Unlity::ConverDate('d-m-Y', $post->get('NgayHachToan'), 'Y-m-d');
                $SoTien = $post->get('SoTien');
                $ChungTu = $this->getEntityManager()->find('Application\Entity\chungtu', $SoQDMG);
                $MucLuc = $this->getEntityManager()->find('Application\Entity\muclucngansach', $_TieuMuc);
                /* @var $CTChungTu kythuemg */
                $CTChungTu = $this->getEntityManager()->find('Application\Entity\kythuemg', array(
                    'chungtu' => $ChungTu,
                    'muclucngansach' => $MucLuc,
                    'KyThue' => $_KyThue
                ))

                ;
                
                if ($CTChungTu != null) {
                    
                    $miengiamModel = new miengiamModel($this->getEntityManager());
                    $CTChungTu->setMuclucngansach($MucLuc);
                    $CTChungTu->setChungtu($ChungTu);
                    //$CTChungTu->setNgayHachToan($NgayHachToan);
                    $CTChungTu->setKyThue($KyThue);
                    $CTChungTu->setSoTien($SoTien);
                    
                    $kq = $miengiamModel->merge($CTChungTu);
                } else {
                    
                    $kq->setKq(false);
                    $kq->appentMessenger("Chi tiết chứng từ không tồn tại !");
                }
            }             

            // validation lỗi
            else {
                
                $kq->setKq(false);
                $kq->appentMessenger($this->getErrorMessengerForm($form));
            }
            
            // trả về json
            echo json_encode($kq->toArray());
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            echo json_encode($kq->toArray());
        }
        
        return $this->response;
    }

    /**
     * AjAx
     */
    public function SoChiTietCuaChungTuAction()
    {
        $SoQDMG = $this->getRequest()
            ->getQuery()
            ->get('SoQDMG');
        /* @var $ChungTu chungtu */
        $ChungTu = $this->getEntityManager()->find('Application\Entity\chungtu', $SoQDMG);
        
        $count = $ChungTu->getkythuemgs()->count();
        
        echo json_encode(array(
            'count' => $count
        ));
        return $this->response;
    }

    /**
     * AJAX
     * Trả về danh sách chứng từ giữa 2 ngày
     */
    public function danhSachChungTuGiuaNgayAction()
    {
        $start = $this->getRequest()
            ->getQuery()
            ->get('start');
        $end = $this->getRequest()
            ->getQuery()
            ->get('end');
        
        $chugtuModel = new miengiamModel($this->getEntityManager());
        
        $danhsachchungtuArray = $chugtuModel->DanhSachChungTuGiuaNgay($start, $end, $this->getUser(), 'array');
        echo json_encode($danhsachchungtuArray->toArray());
        return $this->response;
    }

    private function checkMaSoThue($MaSoThue,&$kq)
    {
       
        $ktnnt = new nguoinopthueModel($this->getEntityManager());
        
        if ($ktnnt->ktNNT($MaSoThue, $this->getUser()) == false) {
            $kq->setKq(false);
            $kq->setMessenger("Mã số thuế $MaSoThue không thuộc quyền quản lý của bạn !");
            return false;
        }
        
        return true;
    }

    private function checkSoQD($SoQDMG,&$kq)
    {
        
        $ktSCT = new miengiamModel($this->getEntityManager());
        
        if ($ktSCT->KiemTraSoChungCuaUser($SoQDMG, $this->getUser()) == false) {
            $kq->setKq(false);
            $kq->setMessenger("Số chứng từ $SoQDMG không thuộc quyền quản lý của bạn !");
            return false;
        }
        return true;
    }
}

?>