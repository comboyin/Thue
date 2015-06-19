<?php
namespace Quanlynguoinopthue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\nguoinopthue;

/**
 * NguoinopthueController
 *
 * @author
 *
 * @version
 *
 */
class NguoinopthueController extends baseController
{

    /**
     * Load danh sách và Xóa
     */
    public function indexAction()
    {
        
        
        $request = $this->getRequest();
        $post = $request->getPost();
        
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        
        $dsnguoinopthue = $nguoinopthueModel->DanhSachByIdentity($this->getUser())->getObj();
        
        
        if($post->get("HanhDong")=="xoa")
        {
            
            
            
            return array(
                'dsnnt' => $dsnguoinopthue,
                'kq' => $kq
            );
        }
        
        
       
        
        return array(
            'dsnnt' => $dsnguoinopthue
        );
    }
    
    /**
     * Thêm và sửa
     *   
    */
    public function persitAction()
    {
        
        return array();
    }
    
    // ajax
    public function dsnntbyidentityAction()
    {
    
        $nntModel = new nguoinopthueModel($this->getEntityManager());

        $nnt = $nntModel->getDanhSachByIdentity($this->getUser());

        if ($nnt->getKq() == true) {
            echo json_encode($nnt->getObj(), JSON_UNESCAPED_UNICODE);
        } else {
            $data['loi'] = true;
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return $this->response;
    }
}