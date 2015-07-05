<?php
namespace Quanlysothue\Excel;

use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\dukientruythu;
use Application\Entity\nguoinopthue;
use Application\Entity\muclucngansach;
use Doctrine\ORM\EntityManager;
use Application\base\baseExcel;
use Application\Unlity\Unlity;
use Application\Entity\chungtu;
use Application\Entity\chitietchungtu;
use Application\Entity\ketqua;

class ImportExcelChungTu extends baseExcel
{

    /**
     * Validation file dự kiến import vào
     *
     * @param string $fileName            
     * @param EntityManager $EntityManager            
     * @return string|boolean
     */
    public function CheckFileImport($fileName, $EntityManager)
    {
        $boolErr = 0;
        // Hộ kinh doanh không thuộc sự quản lý của cán bộ thuế đó
        $messKeyExist = "Doanh số này đã được dự kiến !";
        $messMaSoThueNotExist = "Mã số thuế không tồn tại !";
        $messTieuMucNotExist = "Tiểu mục không tồn tại !";
        $ColMaSoThue = 1;
        $ColTenHKD = 2;
        $ColTieuMuc = 3;
        $ColDoanhSo = 4;
        $ColTiLeTinhThue = 5;
        $ColLyDo = 6;
        
        $objPHPExcel = \PHPExcel_IOFactory::load($fileName);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            /*
             * $nrColumns = ord($highestColumn) - 64;
             * echo "<br>The worksheet ".$worksheetTitle." has ";
             * echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
             * echo ' and ' . $highestRow . ' row.';
             * echo '<br>Data: <table border="1"><tr>';
             */
            
            $tempStr = $worksheet->getCellByColumnAndRow(0, 1)->getValue();
            $KyThue = trim(substr($tempStr, strripos($tempStr, '-') + 1));
            
            for ($row = 5; $row <= $highestRow; ++ $row) {
                
                $arrayMessErro = array();
                $MaSoThue = $worksheet->getCellByColumnAndRow($ColMaSoThue, $row)->getValue() . '';
                $TieuMuc = $worksheet->getCellByColumnAndRow($ColTieuMuc, $row)->getValue() . '';
                $DoanhSo = $worksheet->getCellByColumnAndRow($ColDoanhSo, $row)->getValue();
                $TiLeTinhThue = $worksheet->getCellByColumnAndRow($ColTiLeTinhThue, $row)->getValue();
                $SoTien = $DoanhSo * $TiLeTinhThue;
                $TrangThai = 0;
                $LyDo = $worksheet->getCellByColumnAndRow($ColLyDo, $row)->getValue();
                
                // check tieumuc
                $checkTieuMuc = $EntityManager->find('Application\Entity\muclucngansach', $TieuMuc);
                if ($checkTieuMuc == null) {
                    $arrayMessErro[] = $messTieuMucNotExist;
                }
                // check masothue
                $checkMaSoThue = $EntityManager->find('Application\Entity\nguoinopthue', $MaSoThue);
                if ($checkMaSoThue == null) {
                    $arrayMessErro[] = $messMaSoThueNotExist;
                }
                // check key
                if ($checkTieuMuc != null && $checkMaSoThue != null) {
                    $checkKey = $EntityManager->find('Application\Entity\dukientruythu', array(
                        'KyThue' => $KyThue,
                        'nguoinopthue' => $checkMaSoThue,
                        'muclucngansach' => $checkTieuMuc
                    ));
                    if ($checkKey != null) {
                        $arrayMessErro[] = $messKeyExist;
                    }
                }
                
                if (count($arrayMessErro) > 0) {
                    $boolErr = 1;
                    // fill color row
                    $colFist = \PHPExcel_Cell::stringFromColumnIndex(0);
                    $colLast = \PHPExcel_Cell::stringFromColumnIndex($ColLyDo);
                    $strCellsFill = $colFist . $row . ':' . $colLast . $row;
                    $this->cellColor($strCellsFill, 'F28A8C', $objPHPExcel);
                    $LastCol = $ColLyDo + 1;
                    foreach ($arrayMessErro as $messerr) {
                        
                        // add values
                        $worksheet->setCellValueByColumnAndRow($LastCol, $row, $messerr);
                        $LastCol ++;
                    }
                    
                    // create file and save file excel
                    
                    $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                    $fileName = './data/filetmp/' . 'error-' . (new \DateTime())->format("d-m-Y") . '-' . mt_rand() . '.xlsx';
                    
                    $objWriter->save($fileName);
                    // return namefile
                }
            }
        }
        if ($boolErr == 1) {
            return $fileName;
        }
        
        return false;
    }

    /**
     * Đọc dữ liệu từ file excel và thêm vào csdl
     *
     * @param string $fileName            
     * @param EntityManager $em            
     * @return bool
     */
    public function PersitToDatabase($fileName, $em)
    {
        try {
            $kq= new ketqua();
            // begin transaction
            $em->getConnection()->beginTransaction();
            $_SoChungTu = 6;
            $_NgayHachToan = 2;
            $_NgayChungTu = 1;
            $_MaSoThue = 3;
            $_TieuMuc = 7;
            $_SoTien = 8;
            
            $arrayCollection = new ArrayCollection();
            $objPHPExcel = \PHPExcel_IOFactory::load($fileName);
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $worksheetTitle = $worksheet->getTitle();
                $highestRow = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                
                for ($row = 2; $row <= $highestRow; ++ $row) {
                    if ($worksheet->getCellByColumnAndRow(0, $row)->getValue() != '') {
                        // mm/dd/yy
                        
                        $SoChungTu = $worksheet->getCellByColumnAndRow($_SoChungTu, $row)->getValue() . '';

                        // d-m-Y
                        $NgayHachToan =  Unlity::ConverPhpExcelToDateTimeObject($worksheet->getCellByColumnAndRow($_NgayHachToan, $row));
                        
                        $NgayChungTu  =  Unlity::ConverPhpExcelToDateTimeObject($worksheet->getCellByColumnAndRow($_NgayChungTu, $row));
                        $MaSoThue = $worksheet->getCellByColumnAndRow($_MaSoThue, $row)->getValue() . '';
                        $KyThue = Unlity::ConverDate('Y-m-d', $NgayHachToan, 'm/Y');
                        $TieuMuc = $worksheet->getCellByColumnAndRow($_TieuMuc, $row)->getValue() . '';
                        $SoTien = $worksheet->getCellByColumnAndRow($_SoTien, $row)->getValue();
                        $muclucngansach = $em->find('Application\Entity\muclucngansach', $TieuMuc);
                        // new dukientruythu
                        $chungtu = $em->find('Application\Entity\chungtu', $SoChungTu);
                        
                        if ($chungtu == null) {
                            // them moi so chung tu
                            
                            $chungtuTemp = new chungtu();
                            
                            $chungtuTemp->setSoChungTu($SoChungTu);
                            $chungtuTemp->setNgayChungTu($NgayChungTu);
                            $chungtuTemp->setNguoinopthue($em->find('Application\Entity\nguoinopthue', $MaSoThue));
                            
                            $em->persist($chungtuTemp);
                            $chungtuTemp=$em->find('Application\Entity\chungtu', $SoChungTu);
                            $chitietchungtuTemp = new chitietchungtu();
                            $chitietchungtuTemp->setChungtu($chungtuTemp);
                            $chitietchungtuTemp->setNgayHachToan($NgayHachToan);
                            $chitietchungtuTemp->setKyThue($KyThue);
                            $chitietchungtuTemp->setSoTien($SoTien);
                            $chitietchungtuTemp->setMuclucngansach($muclucngansach);
                            
                            $em->persist($chitietchungtuTemp);
                        } else {
                            // chung tu
                            // tieu muc
                            // ky thue
                            
                            $chitietchungtuTemp = $em->find('Application\Entity\chitietchungtu', array(
                                
                                'KyThue' => $KyThue,
                                'muclucngansach' => $muclucngansach,
                                'chungtu' => $chungtu
                            ));
                            if ($chitietchungtuTemp != null) {
                                $chitietchungtuTemp = new chitietchungtu();
                                $chitietchungtuTemp->setChungtu($chungtu);
                                $chitietchungtuTemp->setKyThue($KyThue);
                                $chitietchungtuTemp->setMuclucngansach($muclucngansach);
                                $em->persist($chitietchungtuTemp);
                            }
                        }
                    }
                }
            }
            $em->flush();
            $em->getConnection()->commit();
            $kq->setKq(true);
            
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            $em->getConnection()->rollBack();
            
        }
        unlink($fileName);
        return $kq;
    }
}

?>