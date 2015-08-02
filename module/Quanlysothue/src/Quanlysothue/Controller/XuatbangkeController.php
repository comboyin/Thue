<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Quanlysothue\Excel\Xuatbangke;

class XuatbangkeController extends baseController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        
        $dsnguoinopthue = $nguoinopthueModel->DanhSachByIdentity($this->getUser(), 'object')
            ->getObj();
        
        return array(
            'dsnnt' => $dsnguoinopthue
        );
    }

    public function downloadBangKeAction()
    {
        $dsMaSoThue = $this->getRequest()->getPost()->get("dsMaSoThue");
        $KyThue = $this->getRequest()->getPost()->get("KyThue");
        $model = new Xuatbangke($this->getEntityManager());
        $kq = $model->dowloadBangKe($dsMaSoThue, $KyThue);
        
        echo json_encode($kq);
        
        return $this->response;
    }
    
    public function createChungTuAction(){
        $dsMaSoThue = $this->getRequest()->getPost()->get("dsMaSoThue");
        $KyThue = $this->getRequest()->getPost()->get("KyThue");
        $model = new Xuatbangke($this->getEntityManager());
        $kq = $model->createChungTu($dsMaSoThue, $KyThue,$this->getUser());
        
        echo json_encode($kq);
        
        return $this->response;
    }
}

?>