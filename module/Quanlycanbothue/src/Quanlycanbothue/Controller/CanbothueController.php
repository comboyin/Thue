<?php
namespace Quanlycanbothue\Controller;

use Application\base\baseController;
use Quanlycanbothue\Model\canbothueModel;
use Quanlycanbothue\Form\formCanBoThue;
use Application\Entity\ketqua;
use Zend\Http\Request;
use Zend\Form\Form;
use Application\Entity\user;


class CanbothueController extends baseController
{
    public function indexAction()
    {
        $model = new canbothueModel($this->getEntityManager());
        
        // danh sach theo nam
        $dscanbothue = $model->dscanbothue($this->getUser());
        $form = new formCanBoThue();
        
        if ($this->getUser()->getLoaiUser() == 3) {
            
        }
        return array(
            'dsCanBoThue' => $dscanbothue->getObj(),
            'formCanBoThue' => $form
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
            $ktMaUser = 'NO';
        else
            $ktMaUser = 'YES';

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
            $ktEmail = 'NO';
        else
            $ktEmail = 'YES';
    
        echo json_encode(array(
            'ktEmail' => $ktEmail
        ));
        return $this->response;
    }

}
