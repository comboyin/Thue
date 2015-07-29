<?php
namespace Quanlysothue\Excel;

use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\dukientruythu;
use Application\Entity\nguoinopthue;
use Application\Entity\muclucngansach;
use Doctrine\ORM\EntityManager;
use Application\base\baseExcel;
use Application\Entity\ketqua;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\user;
use Quanlysothue\Models\dukientruythuModel;

class ImportExcelDuKienTruyThu extends baseExcel
{

    /**
     * return:
     * + $kq->getKq() : true|false
     * + $kq->getMessenger(): nội dung kết quả
     *
     * @param EntityManager $EntityManager            
     * @param string $fileName            
     */
    public function CheckFileImport($fileName, $EntityManager, $user)
    {
        $kq = new ketqua();
        $boolErr = 0;
       
        $messKeyExist = "Doanh số này đã được dự kiến !";
        $messMaSoThueNotExist = "Mã số thuế không tồn tại !";
        $messTieuMucNotExist = "Tiểu mục không tồn tại !";
        $messNNTKhongThuocQuanLy = "Người nộp thuế này không thuộc quản lý của bạn hoặc đã nghĩ kinh doanh!";
        $messKiemTraTieuMuc = "Dự kiến chỉ truy thu 1003 và 1701 !";
        $messChuaDuKienThue = "Không tìm thấy dự kiến thuế !";
        $ColMaSoThue = 1;
        $ColTenHKD = 2;
        $ColTieuMuc = 3;
        $ColDoanhSo = 4;
        $ColTiLeTinhThue = 5;
        $ColLyDo = 6;
        $ColCuoi = 9;
        
        $objPHPExcel = \PHPExcel_IOFactory::load($fileName);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            
            $tempStr = $worksheet->getCellByColumnAndRow(0, 1)->getValue();
            $KyThue = trim(substr($tempStr, strripos($tempStr, '-') + 1));
            if ($highestRow >= 5) {
                for ($row = 5; $row <= $highestRow; ++ $row) {
                    
                    $arrayMessErro = array();
                    $MaSoThue = $worksheet->getCellByColumnAndRow($ColMaSoThue, $row)->getValue() . '';
                    $TieuMuc = $worksheet->getCellByColumnAndRow($ColTieuMuc, $row)->getValue() . '';
                    $DoanhSo = $worksheet->getCellByColumnAndRow($ColDoanhSo, $row)->getValue();
                    $TiLeTinhThue = $worksheet->getCellByColumnAndRow($ColTiLeTinhThue, $row)->getValue();
                    $SoTien = $DoanhSo * $TiLeTinhThue;
                    $TrangThai = 0;
                    $LyDo = $worksheet->getCellByColumnAndRow($ColLyDo, $row)->getValue();
                    
                    
                    
                    //**************begin check********************//
                    
                    // check dukienthue
                    $dukienthue = $EntityManager->find('Application\Entity\dukienthue', array(
                       'KyThue'=>$KyThue,
                        'nguoinopthue'=>$EntityManager->find('Application\Entity\nguoinopthue',$MaSoThue),
                        'muclucngansach'=> $EntityManager->find('Application\Entity\muclucngansach',$TieuMuc)
                    ));
                    if($dukienthue==null){
                        $arrayMessErro[]=$messChuaDuKienThue;
                    }
                    
                    // check tieumuc
                    /* @var $checkTieuMuc muclucngansach */
                    $checkTieuMuc = $EntityManager->find('Application\Entity\muclucngansach', $TieuMuc);
                    if ($checkTieuMuc == null) {
                        $arrayMessErro[] = $messTieuMucNotExist;
                    }else if($checkTieuMuc->getTieuMuc() != "1003" && $checkTieuMuc->getTieuMuc() != "1701" ){
                        $arrayMessErro[] = $messKiemTraTieuMuc;
                    }
                    
                    // check masothue
                    $checkMaSoThue = $EntityManager->find('Application\Entity\nguoinopthue', $MaSoThue);
                    if ($checkMaSoThue == null) {
                        $arrayMessErro[] = $messMaSoThueNotExist;
                    } else {
                        $nntModel = new nguoinopthueModel($EntityManager);
                        if ($nntModel->ktNNT($MaSoThue, $user) == false) {
                            $arrayMessErro[] = $messNNTKhongThuocQuanLy;
                        }
                    }
                    // check key
                    if ($checkTieuMuc != null && $checkMaSoThue != null) {
                        $dukientruythuModel = new dukientruythuModel($EntityManager);
                        $checkKey = $dukientruythuModel->findByID_($KyThue, $MaSoThue, $TieuMuc)->getObj(); 
                        if ($checkKey != null) {
                            $arrayMessErro[] = $messKeyExist;
                        }
                    }
                    
                    
                    //**************end check********************//
                    
                    
                    if (count($arrayMessErro) > 0) {
                        $boolErr = 1;
                        // fill color row
                        $colFist = \PHPExcel_Cell::stringFromColumnIndex(0);
                        $colLast = \PHPExcel_Cell::stringFromColumnIndex($ColLyDo);
                        $strCellsFill = $colFist . $row . ':' . $colLast . $row;
                        $this->cellColor($strCellsFill, 'F28A8C', $objPHPExcel);
                        $LastCol = $ColCuoi;
                        foreach ($arrayMessErro as $messerr) {
                            
                            // add values
                            $worksheet->setCellValueByColumnAndRow($LastCol, $row, $messerr);
                            $LastCol ++;
                        }
                    }
                }
                // Thônng báo import thành công
                $kq->setKq(true);
                if ($boolErr == 1) {
                    
                    // create file and save file excel
                    
                    $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                    $fileName = './data/filetmp/' . 'error-' . (new \DateTime())->format("d-m-Y") . '-' . mt_rand() . '.xlsx';
                    $objWriter->save($fileName);
                    // return namefile
                    $kq->setKq(false);
                    $kq->setObj($fileName);
                    $kq->setMessenger('File bạn sử dụng gặp một số vấn đề, một file với các đánh dấu lỗi đã được gởi lại, vui lòng kiểm tra và thử lại !');
                }
            } else {
                $kq->setKq(false);
                $kq->setMessenger('File không đúng định dạng !');
            }
        }
        
        return $kq;
    }

    /**
     * Đọc dữ liệu từ file excel và thêm vào arrayconllection
     *@param EntityManager $entityManager
     * @param string $fileName   
     * @param user $user         
     * @return ketqua
     */
    public function PersitToArrayCollection($fileName,$user,$entityManager)
    {
        try {
            $dem=0;
            $arrayCollection = new ArrayCollection();
            $objPHPExcel = \PHPExcel_IOFactory::load($fileName);
            $entityManager->getConnection()->beginTransaction();
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $worksheetTitle = $worksheet->getTitle();
                $highestRow = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            
            
                $tempStr = $worksheet->getCellByColumnAndRow(0, 1)->getValue();
                $KyThue = trim(substr($tempStr, strripos($tempStr, '-') + 1));
            
                for ($row = 5; $row <= $highestRow; ++ $row) {
            
            
                    $MaSoThue = $worksheet->getCellByColumnAndRow(1, $row)->getValue() . '';
                    $TieuMuc = $worksheet->getCellByColumnAndRow(3, $row)->getValue() . '';
                    $DoanhSo = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $TiLeTinhThue = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $SoTien = $DoanhSo * $TiLeTinhThue;
                    $TrangThai = 0;
                    $LyDo = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    // new dukientruythu
                    $dukientruythuTemp = new dukientruythu();
                    //set dukienthue
                    $dukienthue = $entityManager->find('Application\Entity\dukienthue', array(
                        'KyThue'=>$KyThue,
                        'nguoinopthue'=>$entityManager->find('Application\Entity\nguoinopthue',$MaSoThue),
                        'muclucngansach'=> $entityManager->find('Application\Entity\muclucngansach',$TieuMuc)
                    ));
                    
                    $dukientruythuTemp->setDukienthue($dukienthue);
            
                    $dukientruythuTemp->setUser($user);
            
                    $dukientruythuTemp->setDoanhSo($DoanhSo);
                    $dukientruythuTemp->setTiLeTinhThue($TiLeTinhThue);
                    $dukientruythuTemp->setSoTien($SoTien);
                    $dukientruythuTemp->setTrangThai($TrangThai);
                    $dukientruythuTemp->setLyDo($LyDo);
            
                    // add vao array
            
                    $entityManager->persist($dukientruythuTemp);
                    
                    $dem++;
                }
            }
            $entityManager->flush();
            $entityManager->getConnection()->commit();
            $kq = new ketqua();
            $kq->setKq(true);
            $kq->setObj($KyThue);
            $kq->setMessenger("Import dữ liệu thành công, có $dem dự kiến truy thu được thêm thành công !");
            return $kq;
            
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollBack();
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
     }
}

?>