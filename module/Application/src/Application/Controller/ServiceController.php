<?php
namespace Application\Controller;

use Application\base\baseController;
use Application\Models\chungtuModel;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class ServiceController extends baseController
{

    /**
     * AJAX
     * download file neu file ton tai
     */
    public function downloadFileAction()
    {
        $fileNameErr = $this->request->getQuery()->get('filename');
        
        if (file_exists($fileNameErr)) {
            
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $fileNameErr);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileNameErr)); // $file));
            ob_clean();
            flush();
            // readfile($file);
            readfile($fileNameErr);
            unlink($fileNameErr);
            exit();
        }
        $this->response;
    }

    /**
     * AJAX
     * Trả về danh sách chứng từ giữa 2 ngày
     */
    public function danhSachChungTuGiuaNgayAction()
    {
        $start = $this->getRequest()
            ->getQuery()
            ->get('start');
        $end = $this->getRequest()
            ->getQuery()
            ->get('end');
        
        $chugtuModel = new chungtuModel($this->getEntityManager());
        
        $danhsachchungtuArray = $chugtuModel->DanhSachChungTuGiuaNgay($start, $end);
        echo json_encode($danhsachchungtuArray->toArray());
        return $this->response;
    }

    /**
     * AJAX
     * Trả về danh sách danh sách người nộp thuế của user đó
     */
    public function danhsachNNTAction()
    {
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        echo $nguoinopthueModel->dsNNTbyUser($this->getUser());
        return $this->response;
    }

    /**
     * AJAX
     *
     * Trả về danh sách chi tiết chứng từ theo Số chứng từ
     */
    public function danhSachChiTietChungTuAction()
    {
        error_reporting(0);
        $SoChungTu = $this->getRequest()
            ->getQuery()
            ->get('SoChungTu');
        $chungtuModel = new chungtuModel($this->getEntityManager());
        

        
       
        echo json_encode($chungtuModel->DanhSachChiTietChungTu($SoChungTu)->toArray());

        return $this->response;
    }
    /**
     * AJAX
     * Lấy danh sách mục luc ngân sách
     * @return \Zend\Mvc\Controller\Response  */
    public function muclucngansachAction()
    {
        $bq = $this->getEntityManager()->createQueryBuilder();
        $bq->select('tieumuc')->from('Application\Entity\muclucngansach', 'tieumuc');
        echo json_encode($bq->getQuery()->getArrayResult());
        return $this->response;
    }
}

?>