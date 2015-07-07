<?php
namespace Quanlycanbothue\Controller;

use Application\base\baseController;
use Quanlycanbothue\Model\canbothueModel;
use Quanlycanbothue\Form\formCanBoThue;
use Application\Entity\ketqua;
use Application\Entity\user;


class CanbothueController extends baseController
{
    public function indexAction()
    {
        $kq = new ketqua();
        
        $model = new canbothueModel($this->getEntityManager());

        $form = new formCanBoThue();   
        
        try {
            if($this->getRequest()->getPost()->get('HanhDong') != null)
            {
                $request = $this->getRequest();
                $post = $request->getPost();
                $form->setData($post);
                $HanhDong = $post->get('HanhDong');
                
                
                if ($form->isValid()) {
                    if ($this->getUser()->getLoaiUser() == 3) {
                        if($HanhDong == "them")
                        {
                            $MaUser = $post->get('MaUser');
                            $LoaiUser = 4;
                            $TenUser = $post->get('TenUser');
                            $Email = $post->get('Email');
                            $MatKhau = $post->get('MatKhau');
                            if(isset($post->get('TrangThai')))
                                $TrangThai = 1;
                            else
                                $TrangThai = 0;
                            
                            //coquanthue
                            $macoquan = $this->getUser()->getCoquanthue()->getMaCoQuan();
                            $coquanthue = $this->getEntityManager()->find('Application\Entity\coquanthue', $macoquan);
                            
                            //them
                            $user = new user();
                            
                            $user->setMaUser($MaUser);
                            $user->setTenUser($TenUser);
                            $user->setLoaiUser($LoaiUser);
                            $user->setEmail($Email);
                            $user->setMatKhau($MatKhau);
                            $user->setTrangThai($TrangThai);
                            $user->setCoquanthue($coquanthue);
                            $kq = $model->them($user);
                        }
                    } else {
                        $kq->setKq(false);
                        $kq->setMessenger("Chức năng này không thuộc quyền của bạn!");
                    }
                } else {
                    $mss = $this->getErrorMessengerForm($form);
                    $kq->setKq(false);
                    $kq->setMessenger($mss);
                }
    
            }
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
        }
        
        $dscanbothue = $model->dscanbothue($this->getUser());
        return array(
            'dsCanBoThue' => $dscanbothue->getObj(),
            'formCanBoThue' => $form,
            'kq' => $kq
        );
    }
    
    public function themAction()
    {
        
    }
    
    public function xoaAction()
    {
        
    }
    
    public function suaAction()
    {
        
    }
    
    public function xoanhieuAction()
    {
        
    }

    
    public function ktMaUserAction()
    {
        $MaUser = $this->getRequest()
        ->getQuery()
        ->get('MaUser');
    
        $model = new canbothueModel($this->getEntityManager());
        
        // danh sach theo nam
        if($model->findByID_($MaUser)->getObj() != null)
            $ktMaUser = false;
        else
            $ktMaUser = true;

        echo json_encode(array(
            'ktMaUser' => $ktMaUser
        ));
        return $this->response;
    }
    
    public function ktEmailAction()
    {
        $Email = $this->getRequest()
        ->getQuery()
        ->get('Email');
    
        $model = new canbothueModel($this->getEntityManager());
    
        // danh sach theo nam
        if($model->findByEmail_($Email)->getObj() != null)
            $ktEmail = false;
        else
            $ktEmail = true;
    
        echo json_encode(array(
            'ktEmail' => $ktEmail
        ));
        return $this->response;
    }

}
