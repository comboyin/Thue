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
            
            // var_dump($post);
            
            $form->setData($post);
            
            if ($form->isValid()) {
                $data = $form->getData();
                $fileName = $data['file-excel']['tmp_name'];
                $model = new \Application\Models\dukientruythuModel($this->getEntityManager());
                
                $ImportData = new ImportExcelChungTu();
                
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
                    if ($kq == true) {
                        echo json_encode(array(
                            'sucess' => true,
                            'mess' => 'Import thành công vui lòng chọn kỳ thuế để kiểm tra !'
                        ));
                    } else {
                        echo json_encode(array(
                            'sucess' => false,
                            'mess' => 'Import không thành công, file này có một vài lỗi !'
                        ));
                    }
                    
                    return $this->response;
                }
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
            $NgayHachToan = $post->get('NgayHachToan');
            
            $ChiTietCt = new chitietchungtu();
            $ChiTietCt->setKyThue($KyThue);
            $ChiTietCt->setMuclucngansach($this->getEntityManager()
                ->find('Application\Entity\muclucngansach', $TieuMuc));
            $ChiTietCt->setSoTien($SoTien);
            $ChiTietCt->setChungtu($this->getEntityManager()
                ->find('Application\Entity\chungtu', $SoChungTu));
            $ChiTietCt->setNgayHachToan($NgayHachToan);
            
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
            $chungtu = new chungtu();
            $chungtu->setSoChungTu($SoChungTu);
            $chungtu->setNgayHachToan($NgayChungTu);
            $chungtu->setNguoinopthue($this->getEntityManager()
                ->find('Application\Entity\nguoinopthue', $MaSoThue));
            
            $kq = $chungtuModel->them($chungtu);
        } else { // validation lỗi
            $mss = $this->getErrorMessengerForm($form);
            $kq->setKq(false);
            $kq->setMessenger($kq->getMessenger() . "\n" . $mss);
        }
        
        // trả về json
        echo json_encode($kq->toArray());
        return $this->response;
    }
}

?>