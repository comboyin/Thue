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

class DukientruythuController extends baseController
{

    /**
     * Hiển thị danh sách dự kiến truy thu theo kỳ thuế gần nhất.
     */
    public function indexAction()
    {
        // Lấy kỳ thuế gần nhất
        // to String 'm-Y'
        $today = (new \DateTime())->format('m/Y');
        
        $model = new dukientruythuModel($this->getEntityManager());
        
        // danh sach theo ky thue
        $dsDuKienTruyThu = $model->dsdukiendsbykythue($today, $this->getUser());
        
        /*
         * var_dump($dsDuKienTruyThu);
         *
         * return $this->response;
         */
        return array(
            'dsDuKienTruyThu' => $dsDuKienTruyThu->getObj()
        );
    }
    
    // ajax lay danh sach NNT
    public function danhsachNNTAction()
    {
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        echo $nguoinopthueModel->dsNNTbyUser($this->getUser());
        return $this->response;
    }

    public function muclucngansachAction()
    {
        $bq = $this->getEntityManager()->createQueryBuilder();
        $bq->select('tieumuc')->from('Application\Entity\muclucngansach', 'tieumuc');
        echo json_encode($bq->getQuery()->getArrayResult());
        return $this->response;
    }

    public function themAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        /* @var $request Request */
        /* @var $form Form */
        $request = $this->getRequest();
        $kq = new ketqua();
        $dukientruythu = new dukientruythu();
        $form = new formDuKienTruyThu();
        $form->setInputFilter($dukientruythu->getInputFilter());
        $form->setData($request->getPost());
        
        // validation thanh cong
        if ($form->isValid()) {
            $post = $request->getPost();
            // them
            $KyThue = $post->get('KyThue');
            $MaSoThue = $post->get('MaSoThue');
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
            
            $dukientruythuModel = new dukientruythuModel($this->getEntityManager());
            $kq = $dukientruythuModel->them($dukientruythu);
        }  // validation lỗi
        else {
            $mss = $this->getErrorMessengerForm($form);
            $kq->setKq(false);
            $kq->setMessenger($kq->getMessenger() . "\n" . $mss);
        }
        
        // trả về json
        echo json_encode($kq->toArray());
        return $this->response;
    }
    
    // ajax
    public function xoaAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        $MaSoThue = $post->get('_MaSoThue');
        $KyThue = $post->get('_KyThue');
        $TieuMuc = $post->get('_TieuMuc');
        
        $kq = new ketqua();
        $mss = "";
        
        // validation
        // kiem tra masothue
        
        // xoa trong csdl
        $mo = new dukientruythuModel($this->getEntityManager());
        $dukientruythu = $mo->findByID_($KyThue, $MaSoThue, $TieuMuc)->getObj();
        if ($dukientruythu != null) {
            $kq->setKq(true);
            $kq = $mo->remove($dukientruythu);
        } else {
            $kq->setKq(false);
            $mss .= 'Không tìm đuọc du kiến truy thu !';
        }
        
        $kq->setMessenger($kq->getMessenger() . $mss);
        echo json_encode($kq->toArray());
        return $this->response;
    }

    public function suaAction()
    {
        error_reporting(0);
        try {
            /* @var $request Request */
            /* @var $form Form */
            $request = $this->getRequest();
            $post = $request->getPost();
            $kq = new ketqua();
            $dukientruythu = new dukientruythu();
            $form = new formDuKienTruyThu();
            $form->setInputFilter($dukientruythu->getInputFilter());
            $form->setData($post);
            
            // validation thanh cong
            if ($form->isValid()) {
                
                // sua
                
                // tim dukientruythu
                
                $dukientruythuModel = new dukientruythuModel($this->getEntityManager());
                /* @var $dukientruythu dukientruythu */
                $dukientruythu = $dukientruythuModel->findByID_($post->get('_KyThue'), $post->get('_MaSoThue'), $post->get('_TieuMuc'))
                    ->getObj();
                
                $KyThue = $post->get('_KyThue');
                $MaSoThue = $post->get('MaSoThue');
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
                
                $kq = $dukientruythuModel->merge($dukientruythu);
            }             

            // validation lỗi
            else {
                $mss = $this->getErrorMessengerForm($form);
                $kq->setKq(false);
                $kq->setMessenger($kq->getMessenger() . "\n" . $mss);
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

    public function xoanhieuAction()
    {
        $post = $this->getRequest()->getPost();
        $KyThue = $post->get('_KyThue');
        $MaSoThueData = $post->get("MaSoThueData");
        $TieuMucData = $post->get("TieuMucData");
        $model = new dukientruythuModel($this->getEntityManager());
        $dem = 0;
        for($i=0;$i<count($MaSoThueData);$i++)
        {
            $dukientruythu = $model->findByID_($KyThue, $MaSoThueData[$i], $TieuMucData[$i])->getObj();
            
            if($dukientruythu !=null)
            {
                $model->remove($dukientruythu);
                $dem++;
            }
            
        }
        $kq = new ketqua();
        $kq->setKq(true);
        $kq->setMessenger("Đã xóa ".$dem . " mục");
        echo json_encode($kq->toArray());
        return $this->response;
    }
    /**
     * Trả về danh sách dự kiến truy thu
     *   */
    public function dsDKTTJsonAction(){
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
}