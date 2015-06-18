<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Froms\FormDKThue;
use Zend\Form\Element;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\dukienthue;
use Application\Entity\muclucngansach;
use Application\Entity\nguoinopthue;
use Quanlysothue\Models\dukienthueModel;

class DukienthueController extends baseController
{

    /**
     * load ds
     * xóa 1
     * xóa nhiều
     */
    public function indexAction()
    {
        // get ds
        $request = $this->getRequest();
        
        $model = new dukienthueModel($this->getEntityManager());
        
        // xoa n
        if ($request->isPost() && $request->getPost()->get('HanhDong') == 'XoaN') {
            
            return array(
                'kq' => $kq
            );
        }  // xoa1
        else 
            if ($request->isPost() && $request->getPost()->get('HanhDong') == "Xoa1") {
                
                return array(
                    'kq' => $kq
                );
            }
        
        return array(
            'form' => $form
        );
    }

    /**
     * Thêm và sửa
     */
    public function persistAction()
    {
        try {
            // khởi tạo form
            $form = new FormDKThue();
            /* @var $HanhDongElem Element */
            $HanhDongElem = $form->get('HanhDong');
            if ($this->getRequest()->isGet()) {
                $HanhDongElem->setAttribute('value', 'Them');
            }
            
            if ($this->getRequest()->isPost()) {
                
                $post = $this->getRequest()->getPost();
                // Sua
                if ($post->get('HanhDong') == 'Sua') {
                    $dkt = new dukienthue();
                    $form->setInputFilter($dkt->getInputFilter());
                    $form->setData($this->getRequest()
                        ->getPost());
                    if ($form->isValid()) {
                        // Viet ham sua
                    }
                } else  // Them
                    if ($post->get('HanhDong') == 'Them') {
                        
                        $dkt = new dukienthue();
                        
                        $form->setInputFilter($dkt->getInputFilter());
                        $form->setData($post);
                        
                        if ($form->isValid()) {
                            
                            // Viet ham them
                            // new mucluc
                            $mucluc = new muclucngansach();
                            $mucluc->setTieuMuc($post->get('TieuMuc'));
                            // new nguoinopthue
                            $nguoinopthue = new nguoinopthue();
                            $nguoinopthue->setMaSoThue($post->get('MaSoThue'));
                            
                            // new dkt
                            $dkt->setKyThue($post->get('KyThue'));
                            $dkt->setNguoinopthue($this->getEntityManager()
                                ->find('Application\Entity\nguoinopthue', $post->get('MaSoThue')));
                            $dkt->setMuclucngansach($this->getEntityManager()
                                ->find('Application\Entity\muclucngansach', $post->get('TieuMuc')));
                            $dkt->setUser($this->getUser());
                            
                            $model = new dukienthueModel($this->getEntityManager());
                            $kq = $model->them($dkt);
                            
                            return array(
                                'kq' => $kq,
                                'form' => $form
                            );
                        }
                    }  // NewSua
else 
                        if ($post->get('HanhDong') == 'NewSua') {
                            // tim
                            
                            // setAttr cho form
                            
                            // trả form to view
                            return array(
                                'form' => $form
                            );
                        }
            }
            // NewThem
            // form to view
            return array(
                'form' => $form
            );
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
    // ***************************************
    // ajax action
    // ***************************************
    public function dsNNTChuaCoDKTAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $kythue = $request->getPost()->get('KyThue');
            $tieumuc = $request->getPost()->get('TieuMuc');
            $model = new nguoinopthueModel($this->getEntityManager());
            $a = $model->dsNNTKhongCoDuKienThue($kythue, $tieumuc, $this->getUser());
            /* @var $dsnguoinopthue nguoinopthue */
            $dsnguoinopthue = $a->getObj();
            
            // var_dump($dsnguoinopthue);
            
            echo json_encode($dsnguoinopthue, JSON_UNESCAPED_UNICODE);
        }
        
        return $this->response;
    }

    public function dsMucLucNganSachAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $em = $this->getEntityManager();
            
            $qb = $em->createQueryBuilder()
                ->select(array(
                'muclucngansach'
            ))
                ->from('Application\Entity\muclucngansach', 'muclucngansach');
            
            echo json_encode($qb->getQuery()->getArrayResult(), JSON_UNESCAPED_UNICODE);
        }
        
        return $this->response;
    }
    
    // ajax action
    
    /*
     * public function testAction()
     * {
     * $model = new nguoinopthueModel($this->getEntityManager());
     * $model->dsNNTKhongCoDuKienThue('','',$this->getUser());
     * return $this->response;
     * }
     */
}