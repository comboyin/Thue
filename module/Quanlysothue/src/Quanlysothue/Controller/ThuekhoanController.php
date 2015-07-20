<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\thuekhoanModel;
use Quanlysothue\Models\dukienthuecuathangModel;
use Application\Entity\ketqua;

class ThuekhoanController extends baseController
{

    public function indexAction()
    {
        $thuekhoanModel = new thuekhoanModel($this->getEntityManager());
        $temp = explode('/', (new \DateTime())->format('d/m/Y'));
        $Thang = $temp[1] . '/' . $temp[2];
        
        $dsThueKhoan = $thuekhoanModel->dsThueKhoan($Thang, $this->getUser(), 'object')
            ->getObj();
        
        return array(
            'dsThueKhoan' => $dsThueKhoan
        );
    }

    public function dsThueKhoanAction()
    {
        $Thang = $this->getRequest()
            ->getQuery()
            ->get('Thang');
        $model = new thuekhoanModel($this->getEntityManager());
        echo json_encode($model->dsThueKhoan($Thang, $this->getUser(), 'array')
            ->toArray());
        return $this->response;
    }

    public function DSDKThueKhoanAction()
    {
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new dukienthuecuathangModel($this->getEntityManager());
        echo json_encode($model->DSDKThueKhoan($Thang, $this->getUser(), 'array'));
        return $this->response;
    }

    public function ghisoAction()
    {
        $dsMaSoThue = $this->getRequest()
            ->getPost()
            ->get('dsMaSoThue');
        $dsTieuMuc = $this->getRequest()
            ->getPost()
            ->get('dsTieuMuc');
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new thuekhoanModel($this->getEntityManager());
        $kq = $model->ghiso($dsMaSoThue, $dsTieuMuc, $Thang)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function duyetAction()
    {
        $dsMaSoThue = $this->getRequest()
            ->getPost()
            ->get('dsMaSoThue');
        $dsTieuMuc = $this->getRequest()
            ->getPost()
            ->get('dsTieuMuc');
        $Thang = $this->getRequest()
            ->getPost()
            ->get('Thang');
        $model = new thuekhoanModel($this->getEntityManager());
        $kq = $model->duyet($dsMaSoThue, $dsTieuMuc, $Thang)->toArray();
        
        echo json_encode($kq);
        return $this->response;
    }

    public function xoaAction()
    {
        $model = new thuekhoanModel($this->getEntityManager());
        $kq= new ketqua();
        $MaSoThue = $this->getRequest()
            ->getPost()
            ->get('_MaSoThue');
        $KyThue = $this->getRequest()
            ->getPost()
            ->get('_KyThue');
        $TieuMuc = $this->getRequest()
            ->getPost()
            ->get('_TieuMuc');
        
        /* @var $thue thue */
        $thue = $this->em->find('Application\Entity\thue', array(
            'nguoinopthue' => $this->em->find('Application\Entity\nguoinopthue', $MaSoThue),
            'muclucngansach' => $this->em->find('Application\Entity\muclucngansach', $TieuMuc),
            'KyThue' => $KyThue
        ));
        
        if($thue==null){
            $kq->setKq(false);
            $kq->setMessenger('<span style="color : red;">Không tìm thấy thuế !</span>');
            echo json_encode($kq->toArray());
            return $this->response;
            
        }
        
        $kq = $model->remove($thue)->toArray();    
        
        echo json_encode($kq);
        return $this->response;
    }
}

?>