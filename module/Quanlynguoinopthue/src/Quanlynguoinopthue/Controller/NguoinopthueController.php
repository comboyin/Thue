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
     * The default action - show the home page
     */
    public function indexAction()
    {
        
        $request = $this->get
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        
        $dsnguoinopthue = $nguoinopthueModel->DanhSachByIdentity($this->getUser())->getObj();
       
        
        return array(
            'dsnnt' => $dsnguoinopthue
        );
    }
    
    public function persitAction()
    {
        return $this->response;
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