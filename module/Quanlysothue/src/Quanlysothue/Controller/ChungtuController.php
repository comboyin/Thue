<?php
namespace Quanlysothue\Controller;
use Application\base\baseController;
use Quanlysothue\Froms\FormChungTu;

class ChungtuController extends baseController
{
    
    public function indexAction(){
            
    }
    
    
    public function themAction(){


        error_reporting(E_ERROR | E_PARSE);
        
        /* @var $request Request */
        /* @var $form Form */
        $request = $this->getRequest();
        $kq = new ketqua();
       
        
        
        $form = new FormChungTu();

        $form->setData($request->getPost());
        
        
        
      
        
        
        // validation thanh cong
        if ($form->isValid()) {
            $post = $request->getPost();
            $nganhModel = new nganhModel($this->getEntityManager());
            // them
            $NgayChungTu = $post->get('NgayChungTu');
            $NgayHachToan = $post->get('NgayHachToan');
            $SoChungTu = $post->get('SoChungTu');
            $MaSoThue = $post->get('MaSoThue');
        
        
            $TrangThai = 0;
            $LyDo = $post->get('LyDo');
            $TiLeTinhThue = $nganhModel->getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue, $TieuMuc);
        
            $SoTien = $DoanhSo*$TiLeTinhThue;
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
        
            $dukientruythuModel = new dukientruythuModel($this->getEntityManager());
            $kq = $dukientruythuModel->them($dukientruythu);
        }  else { // validation lỗi
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