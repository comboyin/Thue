<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Application\Models\chungtuModel;

class ChamboController extends baseController
{

    public function indexAction()
    {
        $today = (new \DateTime())->format('m/Y');
        $Thang = explode('/', $today)[0];
        $Nam = explode('/', $today)[1];
        $NgayDauKyDate = \DateTime::createFromFormat('d-m-Y', "01-$Thang-$Nam");
        $NgayDauKyString = $NgayDauKyDate->format('Y-m-d');
        $NgayCuoiKy = $NgayDauKyDate->format('Y-m-t');
        $ChungTuModel = new chungtuModel($this->getEntityManager());
        
        $ChungTus = $ChungTuModel->DanhSachChungTuGiuaNgay2($NgayDauKyString, $NgayCuoiKy, $this->getUser(), 'object')
            ->getObj();
        return array(
            'chungtus' => $ChungTus
        );
    }

    public function loadChungTuAction()
    {
        $KyThue = $this->getRequest()->getQuery()->get('KyThue');
        
        $Thang = explode('/', $KyThue)[0];
        $Nam = explode('/', $KyThue)[1];
        $NgayDauKyDate = \DateTime::createFromFormat('d-m-Y', "01-$Thang-$Nam");
        $NgayDauKyString = $NgayDauKyDate->format('Y-m-d');
        $NgayCuoiKy = $NgayDauKyDate->format('Y-m-t');
        $ChungTuModel = new chungtuModel($this->getEntityManager());
        
        $ChungTus = $ChungTuModel->DanhSachChungTuGiuaNgay2($NgayDauKyString, $NgayCuoiKy, $this->getUser(), 'array')
            ->toArray();
        echo json_encode($ChungTus);
        return $this->response;
    }
    public function ChamboAction()
    {
        $KyThue = $this->getRequest()->getPost()->get('KyThue');
        $dsSoChungTu = $this->getRequest()->getPost()->get('dsSoChungTu');
        
        $ChungTuModel = new chungtuModel($this->getEntityManager());
        $kq = $ChungTuModel->Chambo($dsSoChungTu, $this->getUser());
        $kq->setObj($KyThue);
        
        echo json_encode($kq->toArray());
        
        
        return $this->response;
    }
}

?>