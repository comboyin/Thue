<?php
namespace Application\Controller;

use Application\base\baseController;
use Application\Models\chungtuModel;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Models\miengiamModel;

class ServiceController extends baseController
{

    /**
     * AJAX
     * download file neu file ton tai và xóa file vừa download
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
     * download file neu file ton tai
     */
    public function downloadFileNoDeleteAction()
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
        
        $danhsachchungtuArray = $chugtuModel->DanhSachChungTuGiuaNgay($start, $end,'array');
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
     * Trả về danh sách người nộp thuế của cán bộ thuế đang quản lý
     * Bao gồm : 
     *          + Người nộp thuế tạm ngừng kinh doanh
     *          + Người nộp thuế đang hoạt động
     *          + Người nộp thuế ngưng kinh doanh
     * AJAX
     */
    public function DanhSachByIdentityAction()
    {
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        echo json_encode($nguoinopthueModel->DanhSachByIdentity($this->getUser(),'array')->getObj());
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
     *
     * Trả về danh sách chi tiết miễn giảm theo Số QDMG
     */
    public function danhSachChiTietMienGiamAction()
    {
        error_reporting(0);
        $SoQDMG = $this->getRequest()
        ->getQuery()
        ->get('SoQDMG');
        $miengiamModel = new miengiamModel($this->getEntityManager());
    
    
    
         
        echo json_encode($miengiamModel->DanhSachChiTietMienGiam($SoQDMG)->toArray());
    
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
    
    /**
     * AJAX
     * Lấy danh sách thông tin ngưng nghĩ
     * @return \Zend\Mvc\Controller\Response  */
    public function thongtinngungnghiAction()
    {
        $MaSoThue = $this->getRequest()
        ->getQuery()
        ->get("MaSoThue");
    
        $ttnn = $this->getEntityManager()
        ->createQueryBuilder()
        ->select(array(
            "thongtinngungnghi"
        ))
        ->from('Application\Entity\thongtinngungnghi', "thongtinngungnghi")
        ->join('thongtinngungnghi.nguoinopthue', "nguoinopthue")
        ->where("nguoinopthue.MaSoThue = ?1")
        ->setParameter(1, $MaSoThue)
        ->getQuery()
        ->getArrayResult();
        echo json_encode($ttnn);
    
        return $this->response;
    }
    
    /**
     * AJAX
     * Lấy danh sách mục luc ngân sách, trừ môn bài
     * @return \Zend\Mvc\Controller\Response  */
    public function mlnsthueAction()
    {
        $bq = $this->getEntityManager()->createQueryBuilder();
        $bq->select('tieumuc')->from('Application\Entity\muclucngansach', 'tieumuc')
        ->where("tieumuc.TieuMuc not like '180_'");
        echo json_encode($bq->getQuery()->getArrayResult());
        return $this->response;
    }
    
    /**
     * AJAX
     * Lấy danh sách mục luc ngân sách, chỉ lấy 1003 và 1701
     * @return \Zend\Mvc\Controller\Response  */
    public function mlnstruythuAction()
    {
        $bq = $this->getEntityManager()->createQueryBuilder();
        $bq->select('tieumuc')->from('Application\Entity\muclucngansach', 'tieumuc')
        ->where("tieumuc.TieuMuc like '1003'")
        ->orWhere("tieumuc.TieuMuc like '1701'");
        echo json_encode($bq->getQuery()->getArrayResult());
        return $this->response;
    }
    
    /**
     * AJAX
     * Lấy danh sách mục luc ngân sách, chỉ lấy môn bài
     * @return \Zend\Mvc\Controller\Response  */
    public function mlnsmonbaiAction()
    {
        $bq = $this->getEntityManager()->createQueryBuilder();
        $bq->select('tieumuc')->from('Application\Entity\muclucngansach', 'tieumuc')
        ->where("tieumuc.TieuMuc like '180_'");
        echo json_encode($bq->getQuery()->getArrayResult());
        return $this->response;
    }
    
    //tiletinhthuetm
    /**
     * AJAX
     * Lấy danh sách biểu thuế tỷ lệ theo tiểu mục
     * @return \Zend\Mvc\Controller\Response  */
    public function tiletinhthuetmAction()
    {
        $tieumuc = $this->getRequest()
        ->getQuery()
        ->get('TieuMuc');
        
        $bq = $this->getEntityManager()->createQueryBuilder();
        $bq->select(array('nganh.MaNganh','nganh.TenNganh','bieuthuetyle.TyLeTinhThue'))->from('Application\Entity\bieuthuetyle', 'bieuthuetyle')
                                   ->join('bieuthuetyle.muclucngansach', 'muclucngansach')
                                   ->join('bieuthuetyle.nganh', 'nganh')
                                   ->where("muclucngansach.TieuMuc = ?1")
                                   ->andwhere('bieuthuetyle.ThoiGianKetThuc is null')
                                   ->setParameter(1, $tieumuc);
        echo json_encode($bq->getQuery()->getArrayResult());
        return $this->response;
    }
    
    /**
     * AJAX
     * Lấy danh sách cán bộ thuế cho chức năng chọn cán bộ thuế, để cập nhật thông tin nnt
     * Chức năng giành cho đội trưởng
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function laydanhsachcbtcapnhatAction()
    {
        $MaCanBoCu = $this->getRequest()
        ->getQuery()
        ->get("MaCanBoCu");
    
        if ($this->getUser()->getLoaiUser() == 3) {
    
            $user = $this->getEntityManager()
            ->createQueryBuilder()
            ->select(array(
                "user"
            ))
            ->from('Application\Entity\user', "user")
            ->where("user.coquanthue = ?1")
            ->andWhere("user not in(" . $this->getEntityManager()
                ->createQueryBuilder()
                ->select("user1")
                ->from('Application\Entity\user', "user1")
                ->where("user1 = ?3")
                ->orWhere("user1.MaUser = ?4")
                ->getDQL() . ")")
                ->setParameter(1, $this->getUser()
                    ->getCoquanthue())
                    ->setParameter(3, $this->getUser())
                    ->setParameter(4, $MaCanBoCu)
                    ->getQuery()
                    ->getArrayResult();
            echo json_encode($user);
        }
    
        return $this->response;
    }
    
}

?>
