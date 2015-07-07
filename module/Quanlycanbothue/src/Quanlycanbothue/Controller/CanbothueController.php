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
        $kq = null;
        $model = new canbothueModel($this->getEntityManager());
        
        $form = new formCanBoThue();
        
        if ($this->getRequest()
            ->getPost()
            ->get('HanhDong') != null) {
            $request = $this->getRequest();
            $post = $request->getPost();
            $form->setData($post);
            $HanhDong = $post->get('HanhDong');
            
            if ($form->isValid()) {
                $kq = new ketqua();
                if ($this->getUser()->getLoaiUser() == 3) {
                    if ($HanhDong == "them") {
                        $MaUser = $post->get('MaUser');
                        $LoaiUser = 4;
                        $TenUser = $post->get('TenUser');
                        $Email = $post->get('Email');
                        $MatKhau = $post->get('MatKhau');
                        if ($post->get('TrangThai') == '1')
                            $TrangThai = 1;
                        else
                            $TrangThai = 0;
                            
                            // coquanthue
                        $macoquan = $this->getUser()
                            ->getCoquanthue()
                            ->getMaCoQuan();
                        $coquanthue = $this->getEntityManager()->find('Application\Entity\coquanthue', $macoquan);
                        
                        // them
                        $user = new user();
                        
                        $user->setMaUser($MaUser);
                        $user->setTenUser($TenUser);
                        $user->setLoaiUser($LoaiUser);
                        $user->setEmail($Email);
                        $user->setMatKhau(md5($MatKhau));
                        $user->setTrangThai($TrangThai);
                        $user->setCoquanthue($coquanthue);
                        $user->setParentUser($this->getUser());
                        $kq = $model->them($user);
                    } else if($HanhDong == "sua"){
                        $MaUser = $post->get('MaUser');
                        $usere = $model->findByID_($MaUser)->getObj();
                        if ($usere != null) {
                            if($model->ktCBT($MaUser, $this->getUser()) == true)
                            {
                                $LoaiUser = 4;
                                $TenUser = $post->get('TenUser');
                                $Email = $post->get('Email');
                                $MatKhau = $post->get('MatKhau');
                                if ($post->get('TrangThai') == '1')
                                    $TrangThai = 1;
                                else
                                    $TrangThai = 0;
                                
                                // coquanthue
                                $macoquan = $this->getUser()
                                ->getCoquanthue()
                                ->getMaCoQuan();
                                $coquanthue = $this->getEntityManager()->find('Application\Entity\coquanthue', $macoquan);
                                
                                // them
                                $usere->setTenUser($TenUser);
                                $usere->setLoaiUser($LoaiUser);
                                $usere->setEmail($Email);
                                $usere->setMatKhau(md5($MatKhau));
                                $usere->setTrangThai($TrangThai);
                                $usere->setCoquanthue($coquanthue);
                                $usere->setParentUser($this->getUser());
                                
                                $kq = $model->merge($usere);
                            }else {
                                $kq->setKq(false);
                                $kq->setMessenger("User này không thuộc quyền quản lý của bạn!");
                            }
                        } else {
                            $kq->setKq(false);
                            $kq->setMessenger('Không tìm được cán bộ thuế có mã này!');
                        }
                    }
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger("Chức năng này không thuộc quyền của bạn!");
                }
            } else {
                $kq = new ketqua();
                $mss = $this->getErrorMessengerForm($form);
                $kq->setKq(false);
                $kq->setMessenger($mss);
            }
        }
        
        $dscanbothue = $model->dscanbothue($this->getUser());
        return array(
            'dsCanBoThue' => $dscanbothue->getObj(),
            'formCanBoThue' => $form,
            'kq' => $kq
        );
    }

    public function themAction()
    {}

    public function xoaAction()
    {}

    public function suaAction()
    {}

    public function xoanhieuAction()
    {}

    public function ktMaUserAction()
    {
        $MaUser = $this->getRequest()
            ->getQuery()
            ->get('MaUser');
        
        $model = new canbothueModel($this->getEntityManager());
        
        // danh sach theo nam
        if ($model->findByID_($MaUser)->getObj() != null)
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
        if ($model->findByEmail_($Email)->getObj() != null)
            $ktEmail = false;
        else
            $ktEmail = true;
        
        echo json_encode(array(
            'ktEmail' => $ktEmail
        ));
        return $this->response;
    }
    
    public function ktEmail2Action()
    {
        $Email = $this->getRequest()
        ->getQuery()
        ->get('Email');
        
        $MaUser = $this->getRequest()
        ->getQuery()
        ->get('MaUser');
    
        $model = new canbothueModel($this->getEntityManager());
    
        // danh sach theo nam
        if ($model->findByEmail2_($Email, $MaUser)->getObj() != null)
            $ktEmail = false;
        else
            $ktEmail = true;
    
        echo json_encode(array(
            'ktEmail' => $ktEmail
        ));
        return $this->response;
    }
    
    public function loadAction()
    {
        $MaUser = $this->getRequest()
        ->getPost()
        ->get('_MaUser');
    
        $model = new canbothueModel($this->getEntityManager());
    
        // danh sach theo nam
        $kq = $model->findByID_aj($MaUser);

    
        echo json_encode($kq);
        return $this->response;
    }
}
