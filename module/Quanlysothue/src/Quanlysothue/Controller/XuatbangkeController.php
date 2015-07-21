<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Models\nguoinopthueModel;
class XuatbangkeController extends baseController
{
    public function indexAction(){
        $request = $this->getRequest();
        $post = $request->getPost();
        
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        
        $dsnguoinopthue = $nguoinopthueModel->DanhSachByIdentity($this->getUser(),'object')->getObj();
        
        
        return array(
            'dsnnt' => $dsnguoinopthue
        );
    }
    
}

?>