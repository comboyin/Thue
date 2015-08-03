<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Froms\FormChungTu;
use Application\Models\chungtuModel;
use Application\Entity\chungtu;
use Application\Entity\ketqua;
use Quanlysothue\Froms\FormCTChungTu;
use Application\Entity\chitietchungtu;
use Application\Forms\UploadForm;
use Quanlysothue\Excel\ImportExcelChungTu;
use Application\Unlity\Unlity;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class ChungtuController extends baseController
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
            
            
            
            $form->setData($post);
            
            if ($form->isValid()) {
                $data = $form->getData();
                $fileName = $data['file-excel']['tmp_name'];
                $model = new \Application\Models\dukientruythuModel($this->getEntityManager());
                
                $ImportData = new ImportExcelChungTu();
                
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
                    
                    $kq = $ImportData->PersitToDatabase($fileName,$this->getEntityManager());
                    
                    $array['sucess'] = $kq->getKq();
                    $array['mess'] = $kq->getMessenger();
                    $array['KyThue']  = $kq->getObj();
                
                    echo json_encode($array);
                    
                }
                
                if(file_exists($fileName)){
                    unlink($fileName);
                }
            }
            else{
                echo json_encode(array(
                    'sucess' => false,
                    'mess' => $this->getErrorMessengerForm($form)
                ));
            }
        }
       
        return $this->response;
    }

    public function themChiTietChungTuAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        /* @var $request Request */
        /* @var $form Form */
        $request = $this->getRequest();
        $kq = new ketqua();
        $form = new FormCTChungTu();
        
        $form->setData($request->getPost());
        
        // validation thanh cong
        if ($form->isValid()) {
            $chungtuModel = new chungtuModel($this->getEntityManager());
            $post = $request->getPost();
            
            // them
            $KyThue = $post->get('KyThue');
            $TieuMuc = $post->get('TieuMuc');
            $SoTien = $post->get('SoTien');
            $SoChungTu = $post->get('SoChungTu');
            if($this->checkSoCT($SoChungTu, $kq)==false){
                echo json_encode($kq->toArray());
                return $this->response;
            }
            $NgayHachToan = $post->get('NgayHachToan');
            
            $ChiTietCt = new chitietchungtu();
            $ChiTietCt->setKyThue($KyThue);
            $ChiTietCt->setMuclucngansach($this->getEntityManager()
                ->find('Application\Entity\muclucngansach', $TieuMuc));
            $ChiTietCt->setSoTien($SoTien);
            $ChiTietCt->setChungtu($this->getEntityManager()
                ->find('Application\Entity\chungtu', $SoChungTu));
            
            $ChiTietCt->setNgayHachToan(Unlity::ConverDate('d-m-Y', $NgayHachToan, 'Y-m-d'));
            
            $kq = $chungtuModel->them($ChiTietCt);
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
            $SoChungTu = $post->get('SoChungTu');
            
            if($this->checkSoCT($SoChungTu,$kq)==false){
                echo json_encode($kq->toArray());
                return $this->response;
            }
            
            
            $chungtuModel = new chungtuModel($this->getEntityManager());
            $ChungTu = $this->getEntityManager()->find('Application\Entity\chungtu', $SoChungTu);
            
            $kq = $chungtuModel->remove($ChungTu);
            
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

    public function xoaCTChungTuAction()
    {
        $kq = new ketqua();
        $SoChungTu = $this->getRequest()
            ->getPost()
            ->get("SoChungTu");
        $KyThue = $this->getRequest()
            ->getPost()
            ->get("KyThue");
        $TieuMuc = $this->getRequest()
            ->getPost()
            ->get("TieuMuc");
        if($this->checkSoCT($SoChungTu, $kq)==false){
            echo json_encode($kq->toArray());
            return $this->response;
        }
        $MucLuc = $this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc);
        $ChungTu = $this->getEntityManager()->find('Application\Entity\chungtu', $SoChungTu);
        
        $chitietchungtu = $this->getEntityManager()->find('Application\Entity\chitietchungtu', array(
            'KyThue' => $KyThue,
            'chungtu' => $ChungTu,
            'muclucngansach' => $MucLuc
        ));
        
        if ($chitietchungtu != null) {
            $model = new chungtuModel($this->getEntityManager());
            $kq = $model->remove($chitietchungtu);
        } else {
            $kq->setKq(false);
            $kq->setMessenger('Không tìm thấy Chi Tiết Chứng Từ này !');
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
            $form = new FormChungTu();
            
            $form->setData($request->getPost());
            
            // validation thanh cong
            if ($form->isValid()) {
                $MaSoThue = $post->get('MaSoThue');
                $SoChungTu = $post->get('SoChungTu');
                if($this->checkMaSoThue($MaSoThue, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                
                
                if($this->checkSoCT($SoChungTu, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                
                /* @var $ChungTu chungtu */
                $ChungTu = $this->getEntityManager()->find('Application\Entity\chungtu', $post->get('_SoChungTu'));
                
                if ($ChungTu != null) {
                    
                    $chungtuModel = new chungtuModel($this->getEntityManager());
                    $ChungTu->setSoChungTu($post->get('SoChungTu'));
                    $ChungTu->setNgayChungTu(Unlity::ConverDate('m-d-Y', $post->get('NgayChungTu'), 'Y-m-d'));
                    $ChungTu->setNguoinopthue($this->getEntityManager()
                        ->find('Application\Entity\nguoinopthue', $post->get('MaSoThue')));
                    $kq = $chungtuModel->merge($ChungTu);
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
        
        $form = new FormChungTu();
        
        $form->setData($request->getPost());
        
        // validation thanh cong
        if ($form->isValid()) {
            $chungtuModel = new chungtuModel($this->getEntityManager());
            $post = $request->getPost();
            
            // them
            $NgayChungTu = $post->get('NgayChungTu');
            $SoChungTu = $post->get('SoChungTu');
            $MaSoThue = $post->get('MaSoThue');
            
            $kt = new nguoinopthueModel($this->getEntityManager());
            if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                $chungtu = new chungtu();
                $chungtu->setSoChungTu($SoChungTu);
                $chungtu->setNgayChungTu(Unlity::ConverDate('d-m-Y', $NgayChungTu, 'Y-m-d'));
                $chungtu->setNguoinopthue($this->getEntityManager()
                    ->find('Application\Entity\nguoinopthue', $MaSoThue));
                
                $kq = $chungtuModel->them($chungtu);
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
            $form = new FormCTChungTu();
            
            $form->setData($request->getPost());
            
            // validation thanh cong
            if ($form->isValid()) {
                $_KyThue = $post->get('_KyThue');
                $_TieuMuc = $post->get('_TieuMuc');
                
                $KyThue = $post->get('KyThue');
                $SoChungTu = $post->get('SoChungTu');
                if($this->checkSoCT($SoChungTu, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                $TieuMuc = $post->get('TieuMuc');
                $NgayHachToan = Unlity::ConverDate('d-m-Y', $post->get('NgayHachToan'), 'Y-m-d');
                $SoTien = $post->get('SoTien');
                $ChungTu = $this->getEntityManager()->find('Application\Entity\chungtu', $SoChungTu);
                $MucLuc = $this->getEntityManager()->find('Application\Entity\muclucngansach', $_TieuMuc);
                /* @var $CTChungTu chitietchungtu */
                $CTChungTu = $this->getEntityManager()->find('Application\Entity\chitietchungtu', array(
                    'chungtu' => $ChungTu,
                    'muclucngansach' => $MucLuc,
                    'KyThue' => $_KyThue
                ))

                ;
                
                if ($CTChungTu != null) {
                    
                    $chungtuModel = new chungtuModel($this->getEntityManager());
                    $CTChungTu->setMuclucngansach($this->getEntityManager()->find('Application\Entity\muclucngansach', $TieuMuc));
                    $CTChungTu->setChungtu($ChungTu);
                    $CTChungTu->setNgayHachToan($NgayHachToan);
                    $CTChungTu->setKyThue($KyThue);
                    $CTChungTu->setSoTien($SoTien);
                    
                    $kq = $chungtuModel->merge($CTChungTu);
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
        $SoChungTu = $this->getRequest()
            ->getQuery()
            ->get('SoChungTu');
        /* @var $ChungTu chungtu */
        $ChungTu = $this->getEntityManager()->find('Application\Entity\chungtu', $SoChungTu);
        
        $count = $ChungTu->getChitietchungtus()->count();
        
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
        
        $chugtuModel = new chungtuModel($this->getEntityManager());
        
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

    private function checkSoCT($SoChungTu,&$kq)
    {
        
        $ktSCT = new chungtuModel($this->getEntityManager());
        
        if ($ktSCT->KiemTraSoChungCuaUser($SoChungTu, $this->getUser()) == false) {
            $kq->setKq(false);
            $kq->setMessenger("Số chứng từ $SoChungTu không thuộc quyền quản lý của bạn !");
            return false;
        }
        return true;
    }
}

?>