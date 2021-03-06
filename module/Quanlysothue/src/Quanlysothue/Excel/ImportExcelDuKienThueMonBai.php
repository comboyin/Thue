<?php
namespace Quanlysothue\Excel;

use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\dukientruythu;
use Application\Entity\nguoinopthue;
use Application\Entity\muclucngansach;
use Doctrine\ORM\EntityManager;
use Application\base\baseExcel;
use Application\Entity\dukienmb;
use Quanlysothue\Models\dukienthuemonbaiModel;
use Application\Entity\ketqua;
use Application\Unlity\Unlity;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class ImportExcelDuKienThueMonBai extends baseExcel
{

    /**
     * Validation file dự kiến import vào
     *
     * @param string $fileName            
     * @param EntityManager $EntityManager            
     * @return string|boolean
     */
    public function CheckFileImport($fileName, $EntityManager, $user)
    {
        $kq = new ketqua();
        $boolErr = 0;
        // Hộ kinh doanh không thuộc sự quản lý của cán bộ thuế đó
        $messKeyExist = "Thuế này đã được dự kiến !";
        $messMaSoThueNotExist = "Mã số thuế không tồn tại !";
        $messTieuMucNotExist = "Tiểu mục không tồn tại !";
        $messNNTKhongThuocQuanLy = "Người nộp thuế này không thuộc quản lý của bạn hoặc đã nghĩ kinh doanh!";
        
        $ColMaSoThue = 1;
        $ColTenHKD = 2;
        $ColTieuMuc = 3;
        $ColBacMonBai = 4;
        $ColDoanhSo = 5;
        $ColNgayPhaiNop = 6;
        $ColSoTien = 7;
        
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
                    
                    $NgayPhaiNop = Unlity::ConverPhpExcelToDateTimeObject($worksheet->getCellByColumnAndRow($ColNgayPhaiNop, $row));
                    $SoTien = $worksheet->getCellByColumnAndRow($ColSoTien, $row)->getValue();
                    
                    // ************* BEGIN CHECK ********************
                    
                    // check tieumuc
                    $checkTieuMuc = $EntityManager->find('Application\Entity\muclucngansach', $TieuMuc);
                    if ($checkTieuMuc == null) {
                        $arrayMessErro[] = $messTieuMucNotExist;
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
                    $checkKey = $EntityManager->find('Application\Entity\dukienmb', array(
                        'Nam' => $KyThue,
                        'nguoinopthue' => $EntityManager->find('Application\Entity\nguoinopthue', $MaSoThue)
                    ));
                    if ($checkKey != null) {
                        $arrayMessErro[] = $messKeyExist;
                    }
                    // ************* END CHECK ********************
                    
                    if (count($arrayMessErro) > 0) {
                        $boolErr = 1;
                        // fill color row
                        $colFist = \PHPExcel_Cell::stringFromColumnIndex(0);
                        $colLast = \PHPExcel_Cell::stringFromColumnIndex(12);
                        $strCellsFill = $colFist . $row . ':' . $colLast . $row;
                        $this->cellColor($strCellsFill, 'F28A8C', $objPHPExcel);
                        $LastCol = 13;
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
     *
     * @param string $fileName            
     * @param string $user            
     * @param EntityManager $entityManager            
     * @return \Quanlysothue\Excel\ketqua
     */
    public function PersitToArrayCollection($fileName, $user, $entityManager)
    {
        try {
            $ColMaSoThue = 1;
            $ColTenHKD = 2;
            $ColTieuMuc = 3;
            $ColBacMonBai = 4;
            $ColDoanhSo = 5;
            $ColNgayPhaiNop = 6;
            $ColSoTien = 7;
            
            $dem = 0;
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
                    $MaSoThue = $worksheet->getCellByColumnAndRow($ColMaSoThue, $row)->getValue() . '';
                    $TieuMuc = $worksheet->getCellByColumnAndRow($ColTieuMuc, $row)->getValue() . '';
                    $DoanhSo = $worksheet->getCellByColumnAndRow($ColDoanhSo, $row)->getValue();
                    $NgayPhaiNop = Unlity::ConverPhpExcelToDateTimeObject($worksheet->getCellByColumnAndRow($ColNgayPhaiNop, $row));
                    $SoTien = $worksheet->getCellByColumnAndRow($ColSoTien, $row)->getValue();
                    $dukienthuemb = new dukienmb();
                    $model = new dukienthuemonbaiModel($entityManager);
                    /* @var $nguoinopthue nguoinopthue */
                    /* @var $muclucngansach muclucngansach */
                    $nguoinopthue = $entityManager->find('Application\Entity\nguoinopthue', $MaSoThue);
                    $muclucngansach = $entityManager->find('Application\Entity\muclucngansach', $TieuMuc);
                    
                    // kt nnt co phai lan dau dc lap du kien thue ko?
                    if ($model->ktMBIn($MaSoThue) == true) {
                        $dateCur = $nguoinopthue->getThoiDiemBDKD();
                        $year = Unlity::stringDateToStringYear($dateCur);
                        $dateBegin = '01-01-' . $year;
                        $dateBegin = '31-06-' . $year;
                        $ktngay = Unlity::CheckDateBetweenTowDate($dateBegin, $dateBegin, $dateCur);
                        if ($ktngay == true) {
                            $SoTien = $muclucngansach->getSoTien();
                        } else {
                            $SoTien = intval($muclucngansach->getSoTien()) / 2;
                        }
                    } else {
                        $SoTien = $muclucngansach->getSoTien();
                    }
                    
                    $dukienthuemb->setNam($KyThue);
                    $dukienthuemb->setNguoinopthue($nguoinopthue);
                    $dukienthuemb->setMuclucngansach($muclucngansach);
                    $dukienthuemb->setDoanhSo($DoanhSo);
                    
                    $dukienthuemb->setNgayPhaiNop($NgayPhaiNop);
                    $dukienthuemb->setSoTien($SoTien);
                    $dukienthuemb->setTrangThai(0);
                    $dukienthuemb->setUser($user);
                    
                    $entityManager->persist($dukienthuemb);
                    
                    
                    $dem ++;
                }
            }
            $entityManager->flush();
            $entityManager->getConnection()->commit();
            $kq = new ketqua();
            $kq->setKq(true);
            $kq->setObj($KyThue);
            $kq->setMessenger("Import dữ liệu thành công, có $dem DỰ KIẾN MÔN BÀI được thêm thành công !");
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