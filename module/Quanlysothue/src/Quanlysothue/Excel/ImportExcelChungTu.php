<?php
namespace Quanlysothue\Excel;

use Doctrine\Common\Collections\ArrayCollection;
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
    public function CheckFileImport($fileName, $EntityManager, $user)
    {
        $kq = new ketqua();
        $boolErr = 0;
        
        $messKeyExist = "Doanh số này đã được dự kiến !";
        $messMaSoThueNotExist = "Mã số thuế không tồn tại !";
        $messTieuMucNotExist = "Tiểu mục không tồn tại !";
        $messNNTKhongThuocQuanLy = "Người nộp thuế này không thuộc quản lý của bạn hoặc đã nghĩ kinh doanh!";
        $messKiemTraTieuMuc = "Dự kiến chỉ truy thu 1003 và 1701 !";
        $_SoChungTu = 6;
        $_NgayHachToan = 2;
        $_NgayChungTu = 1;
        $_MaSoThue = 3;
        $_TieuMuc = 7;
        $_SoTien = 8;
        $_ColLast = 23;
        
        $objPHPExcel = \PHPExcel_IOFactory::load($fileName);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            
            $tempStr = $worksheet->getCellByColumnAndRow(0, 1)->getValue();
            $KyThue = trim(substr($tempStr, strripos($tempStr, '-') + 1));
            if ($highestRow >= 1) {
                for ($row = 2; $row <= $highestRow; ++ $row) {
                    if ($worksheet->getCellByColumnAndRow(0, $row)->getValue() != '') {
                        // mm/dd/yy
                        
                        $SoChungTu = $worksheet->getCellByColumnAndRow($_SoChungTu, $row)->getValue() . '';
                        
                        // d-m-Y
                        $NgayHachToan = Unlity::ConverPhpExcelToDateTimeObject($worksheet->getCellByColumnAndRow($_NgayHachToan, $row));
                        
                        $NgayChungTu = Unlity::ConverPhpExcelToDateTimeObject($worksheet->getCellByColumnAndRow($_NgayChungTu, $row));
                        $MaSoThue = $worksheet->getCellByColumnAndRow($_MaSoThue, $row)->getValue() . '';
                        $KyThue = Unlity::ConverDate('Y-m-d', $NgayHachToan, 'm/Y');
                        $TieuMuc = $worksheet->getCellByColumnAndRow($_TieuMuc, $row)->getValue() . '';
                        $SoTien = $worksheet->getCellByColumnAndRow($_SoTien, $row)->getValue();
                        $muclucngansach = $em->find('Application\Entity\muclucngansach', $TieuMuc);
                        
                        // **************begin check********************//
                        
                        // **************end check********************//
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
     * Đọc dữ liệu từ file excel và thêm vào csdl
     *
     * @param string $fileName            
     * @param EntityManager $em            
     * @return bool
     */
    public function PersitToDatabase($fileName, $em)
    {
        try {
            $kq = new ketqua();
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
                        $NgayHachToan = Unlity::ConverPhpExcelToDateTimeObject($worksheet->getCellByColumnAndRow($_NgayHachToan, $row));
                        
                        $NgayChungTu = Unlity::ConverPhpExcelToDateTimeObject($worksheet->getCellByColumnAndRow($_NgayChungTu, $row));
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
                            $chungtuTemp = $em->find('Application\Entity\chungtu', $SoChungTu);
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
                            if ($chitietchungtuTemp == null) {
                                
                                $chitietchungtuTemp = new chitietchungtu();
                                $chitietchungtuTemp->setChungtu($chungtu);
                                
                                $chitietchungtuTemp->setNgayHachToan($NgayHachToan);
                                $chitietchungtuTemp->setKyThue($KyThue);
                                $chitietchungtuTemp->setSoTien($SoTien);
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