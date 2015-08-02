<?php
namespace Quanlynguoinopthue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Form\FormMienGiam;
use Application\Models\miengiamModel;
use Application\Entity\miengiamthue;
use Application\Entity\ketqua;
use Application\Entity\thue;
use Quanlynguoinopthue\Form\FormCTMienGiam;
use Application\Entity\kythuemg;
use Application\Forms\UploadForm;
use Quanlynguoinopthue\Excel\ImportExcelMienGiam;
use Application\Unlity\Unlity;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\dukienthue;

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
            
            // them
            $post = $request->getPost();
            $KyThue = $post->get('KyThue');
            $TieuMuc = $post->get('TieuMuc');
            $SoTien = $post->get('SoTien');
            $SoQDMG = $post->get('SoQDMG');
            
            //kttontaithue
            /* @var  $mg miengiamthue */
            $mg = $this->getEntityManager()->find('Application\Entity\miengiamthue', $SoQDMG);
            $nnt = $mg->getNguoinopthue();
            /* @var $thue thue */
            $thue = $this->getEntityManager()->find('Application\Entity\thue', array(
                'nguoinopthue'=>$nnt,
                'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $TieuMuc),
                'KyThue' => $KyThue
            ));
            if($thue!=null && $thue->getTrangThai()==1){
                $kq->setKq(false);
                $kq->setMessenger("Sổ thuế này đã được duyệt không thể thêm miễn giảm được !");
                echo json_encode($kq->toArray());
                return $this->response;
            }
            //endkttontai
            
            //ktsotien
            $times = explode('/', $KyThue);
            /* @var $dkthuenam dukienthue */
            $dkthuenam = $this->getEntityManager()->find('Application\Entity\dukienthue', array(
                'nguoinopthue'=>$nnt,
                'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $TieuMuc),
                'KyThue' => $times[1] 
            ));
            if($thue!=null && $SoTien > $thue->getSoTien()){
                $kq->setKq(false);
                $kq->setMessenger("Số tiền miễn giảm phải nhỏ hơn hoặc bằng số tiền phải nộp !");
                echo json_encode($kq->toArray());
                return $this->response;
            }
            elseif ($thue==null && $dkthuenam!=null && $SoTien > $dkthuenam->getSoTien() && $dkthuenam->getTrangThai() == 1){
                $kq->setKq(false);
                $kq->setMessenger("Số tiền miễn giảm phải nhỏ hơn hoặc bằng số tiền khoán !");
                echo json_encode($kq->toArray());
                return $this->response;
            }
            //endktsotien
            
            $miengiamModel = new miengiamModel($this->getEntityManager());

            if($this->checkSoQD($SoQDMG, $kq)==false){
                echo json_encode($kq->toArray());
                return $this->response;
            }
            
            $ChiTietMg = new kythuemg();
            $ChiTietMg->setKyThue($KyThue);
            $ChiTietMg->setMuclucngansach($this->getEntityManager()
                ->find('Application\Entity\muclucngansach', $TieuMuc));
            $ChiTietMg->setSoTienMG($SoTien);
            $ChiTietMg->setMiengiamthue($this->getEntityManager()
                ->find('Application\Entity\miengiamthue', $SoQDMG));
            
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
                $SoQDMG = $post->get('SoQDMG');
                if($this->checkMaSoThue($MaSoThue, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                
                
                if($this->checkSoQD($SoQDMG, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                
                /* @var $MienGiam miengiamthue */
                $MienGiam = $this->getEntityManager()->find('Application\Entity\miengiamthue', $post->get('_SoQDMG'));
                
                if ($MienGiam != null) {
                    
                    $miengiamModel = new miengiamModel($this->getEntityManager());
                    $MienGiam->setSoQDMG($post->get('SoQDMG'));
                    $MienGiam->setNgayCoHieuLuc(Unlity::ConverDate('m-d-Y', $post->get('NgayCoHieuLuc'), 'Y-m-d'));
                    $MienGiam->setLyDo($post->get('LyDo'));
                    $MienGiam->setNguoinopthue($this->getEntityManager()
                        ->find('Application\Entity\nguoinopthue', $post->get('MaSoThue')));
                    
                    $MaTTNgungNghi = $post->get('MaTTNgungNghi');
                    
                    if($MaTTNgungNghi != null && $MaTTNgungNghi != "")
                    {
                        $MienGiam->setThongtinngungnghi($this->getEntityManager()
                            ->find('Application\Entity\thongtinngungnghi', $MaTTNgungNghi));
                    }

                    $kq = $miengiamModel->merge($MienGiam);
                } else {
                    
                    $kq->setKq(false);
                    $kq->appentMessenger("Sô quyết định miễn giảm không tồn tại !");
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
            $NgayCoHieuLuc = $post->get('NgayCoHieuLuc');
            $SoQDMG = $post->get('SoQDMG');
            $MaSoThue = $post->get('MaSoThue');
            $MaTTNgungnghi = $post->get('MaTTNgungnghi');
            
            $kt = new nguoinopthueModel($this->getEntityManager());
            if ($kt->ktNNT($MaSoThue, $this->getUser()) == true) {
                $miengiam = new miengiamthue();
                $miengiam->setSoQDMG($SoQDMG);
                $miengiam->setNgayCoHieuLuc(Unlity::ConverDate('d-m-Y', $NgayCoHieuLuc, 'Y-m-d'));
                $miengiam->setLyDo($post->get('LyDo'));
                $miengiam->setNguoinopthue($this->getEntityManager()
                    ->find('Application\Entity\nguoinopthue', $MaSoThue));
                if($MaTTNgungnghi != null && $MaTTNgungnghi != "")
                {
                    $miengiam->setThongtinngungnghi($this->getEntityManager()
                    ->find('Application\Entity\thongtinngungnghi', $MaTTNgungnghi));
                }
                
                
                $kq = $miengiamModel->them($miengiam);
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

    public function suaCTMienGiamAction()
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
                $TieuMuc = $post->get('TieuMuc');
                $SoTien = $post->get('SoTien');
                //kttontaithue
                /* @var  $mg miengiamthue */
                $mg = $this->getEntityManager()->find('Application\Entity\miengiamthue', $SoQDMG);
                $nnt = $mg->getNguoinopthue();
                /* @var $thue thue */
                $thue = $this->getEntityManager()->find('Application\Entity\thue', array(
                    'nguoinopthue'=>$nnt,
                    'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $TieuMuc),
                    'KyThue' => $KyThue
                ));
                if($thue!=null && $thue->getTrangThai()==1){
                    $kq->setKq(false);
                    $kq->setMessenger("Sổ thuế này đã được duyệt không thể thêm miễn giảm được !");
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                //endkttontai
                
                //ktsotien
                $times = explode('/', $KyThue);
                /* @var $dkthuenam dukienthue */
                $dkthuenam = $this->getEntityManager()->find('Application\Entity\dukienthue', array(
                    'nguoinopthue'=>$nnt,
                    'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $TieuMuc),
                    'KyThue' => $times[1] 
                ));
                if($thue!=null && $SoTien > $thue->getSoTien()){
                    $kq->setKq(false);
                    $kq->setMessenger("Số tiền miễn giảm phải nhỏ hơn hoặc bằng số tiền phải nộp !");
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                elseif ($thue==null && $dkthuenam!=null && $SoTien > $dkthuenam->getSoTien() && $dkthuenam->getTrangThai() == 1){
                    $kq->setKq(false);
                    $kq->setMessenger("Số tiền miễn giảm phải nhỏ hơn hoặc bằng số tiền khoán !");
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                //endktsotien
                
                if($this->checkSoQD($SoQDMG, $kq)==false){
                    echo json_encode($kq->toArray());
                    return $this->response;
                }
                
                //$NgayHachToan = Unlity::ConverDate('d-m-Y', $post->get('NgayHachToan'), 'Y-m-d');
                
                $MienGiam = $this->getEntityManager()->find('Application\Entity\miengiamthue', $SoQDMG);
                $MucLuc = $this->getEntityManager()->find('Application\Entity\muclucngansach', $_TieuMuc);
                /* @var $CTMienGiam kythuemg */
                $CTMienGiam = $this->getEntityManager()->find('Application\Entity\kythuemg', array(
                    'miengiamthue' => $MienGiam,
                    'muclucngansach' => $MucLuc,
                    'KyThue' => $_KyThue
                ));
                
                if ($CTMienGiam != null) {
                    
                    $miengiamModel = new miengiamModel($this->getEntityManager());
                    $CTMienGiam->setMuclucngansach($MucLuc);
                    $CTMienGiam->setMiengiamthue($MienGiam);
                    //$CTChungTu->setNgayHachToan($NgayHachToan);
                    $CTMienGiam->setKyThue($KyThue);
                    $CTMienGiam->setSoTienMG($SoTien);
                    
                    $kq = $miengiamModel->merge($CTMienGiam);
                } else {
                    
                    $kq->setKq(false);
                    $kq->appentMessenger("Chi tiết miễn giảm không tồn tại !");
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
    public function SoChiTietCuaMienGiamAction()
    {
        $SoQDMG = $this->getRequest()
            ->getQuery()
            ->get('SoQDMG');
        /* @var $MienGiam miengiamthue */
        $MienGiam = $this->getEntityManager()->find('Application\Entity\miengiamthue', $SoQDMG);
        
        $count = $MienGiam->getKythuemgs()->count();
        
        echo json_encode(array(
            'count' => $count
        ));
        return $this->response;
    }

    /**
     * AJAX
     * Trả về danh sách miễn giảm giữa 2 ngày
     */
    public function danhSachMienGiamGiuaNgayAction()
    {
        $start = $this->getRequest()
            ->getQuery()
            ->get('start');
        $end = $this->getRequest()
            ->getQuery()
            ->get('end');
        
        $miengiamModel = new miengiamModel($this->getEntityManager());
        
        $danhsachmiengiamArray = $miengiamModel->DanhSachMienGiamGiuaNgay($start, $end, $this->getUser(), 'array');
        echo json_encode($danhsachmiengiamArray->toArray());
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
        
        $ktSQD = new miengiamModel($this->getEntityManager());
        
        if ($ktSQD->KiemTraSoQDMGCuaUser($SoQDMG, $this->getUser()) == false) {
            $kq->setKq(false);
            $kq->setMessenger("Số quyết định miễn giảm $SoQDMG không thuộc quyền quản lý của bạn !");
            return false;
        }
        return true;
    }
}

?>