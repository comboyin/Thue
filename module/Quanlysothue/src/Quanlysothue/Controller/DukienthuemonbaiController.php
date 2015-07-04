<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\dukienthuemonbaiModel;
use Quanlysothue\Froms\formDuKienThueMonBai;
use Application\Entity\ketqua;
use Zend\Http\Request;
use Zend\Form\Form;
use Quanlysothue\Froms\UploadForm;
use Quanlysothue\Excel\ImportExcelDuKienThueCuaNam;
use Application\Entity\dukienmb;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class DukienthuemonbaiController extends baseController
{
    public function indexAction()
    {
         $formUp = new UploadForm('upload-form');
        // Lấy năm gần nhất
        // to String 'Y'
        $today = (new \DateTime())->format('Y');
        
        $model = new dukienthuemonbaiModel($this->getEntityManager());
        
        // danh sach theo nam
        $dsdkthuemb = $model->dsdukienthuemonbai($today, $this->getUser());
        
        return array(
            'formUp' => $formUp,
            'dsDuKienThueMonBai' => $dsdkthuemb->getObj()
        );
    }
    
    public function themAction()
    {
        return array();
    }
    
    public function xoaAction()
    {
        return array();
    }

    public function suaAction()
    {
        return array();
    }

    public function xoanhieuAction()
    {
        return array();
    }
    
    /**
     * Trả về danh sách dự kiến thuế của năm
     */
    public function dsDKTMBJsonAction()
    {
        $kq = new ketqua();
        try {
            $get = $this->getRequest()->getQuery();
    
            $model = new dukienthuemonbaiModel($this->getEntityManager());
    
            // danh sach theo nam
            $dsdkthuemb = $model->dsDKTMBJson($get->get('KyThue'), $this->getUser());
    
            echo json_encode($dsdkthuemb);
            return $this->response;
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            echo json_encode($kq->toArray());
            return $this->response;
        }
    }
}