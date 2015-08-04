<?php
namespace Quanlysothue\Excel;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Application\base\baseExcel;
use Application\Models\nganhModel;

use Application\Entity\dukienthue;
use Application\Entity\ketqua;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class ImportExcelDuKienThueCuaNam extends baseExcel
{

    /**
     * Validation file dự kiến import vào
     *
     * @param string $fileName            
     * @param EntityManager $EntityManager            
     * @return string|boolean
     */
    public function CheckFileImport($fileName, $EntityManager,$user)
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
        $ColDoanhThuChiuThue = 4;
        $ColTiLeTinhThue = 5;
        $ColThueXuat = 6;
        $ColTenGoi = 7;
        $ColSanLuong = 8;
        $ColGiaTinhThue = 9;
        $ColSoTien = 10;
        $ColMaCBT = 11;
        $ColTenCBT = 12;
        $ColCuoi = 13;
        
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
                    $DoanhThuChiuThue = $worksheet->getCellByColumnAndRow($ColDoanhThuChiuThue, $row)->getValue();
                    $TiLeTinhThue = $worksheet->getCellByColumnAndRow($ColTiLeTinhThue, $row)->getValue();
                    $ThueSuat = $worksheet->getCellByColumnAndRow($ColThueXuat, $row)->getValue();
                    $TenGoi = $worksheet->getCellByColumnAndRow($ColTenGoi, $row)->getValue() . '';
                    $SanLuong = $worksheet->getCellByColumnAndRow($ColSanLuong, $row)->getValue();
                    $GiaTinhThue = $worksheet->getCellByColumnAndRow($ColGiaTinhThue, $row)->getValue();
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
                    $checkKey = $EntityManager->find('Application\Entity\dukienthue', array(
                       'KyThue'=>$KyThue,
                        'nguoinopthue'=>$EntityManager->find('Application\Entity\nguoinopthue', $MaSoThue),
                        'muclucngansach'=>$EntityManager->find('Application\Entity\muclucngansach', $TieuMuc) 
                    ));       
                    if($checkKey!=null){
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
     * @return \Quanlysothue\Excel\ketqua*/
    public function PersitToArrayCollection($fileName,$user,$entityManager)
    {
        try {
            $ColMaSoThue = 1;
            $ColTenHKD = 2;
            $ColTieuMuc = 3;
            $ColDoanhThuChiuThue = 4;
            $ColTiLeTinhThue = 5;
            $ColThueXuat = 6;
            $ColTenGoi = 7;
            $ColSanLuong = 8;
            $ColGiaTinhThue = 9;
            $ColSoTien = 10;
            $ColMaCBT = 11;
            $ColTenCBT = 12;
            $ColCuoi = 13;
            
            
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
                    $DoanhThuChiuThueTemp = $worksheet->getCellByColumnAndRow($ColDoanhThuChiuThue, $row)->getValue();
                    $DoanhThuChiuThue = $DoanhThuChiuThueTemp==null?0:$DoanhThuChiuThueTemp;
                    $TiLeTinhThue = $worksheet->getCellByColumnAndRow($ColTiLeTinhThue, $row)->getValue();
                    $ThueSuatTemp = $worksheet->getCellByColumnAndRow($ColThueXuat, $row)->getValue();
                    $ThueSuat = $ThueSuatTemp==null?1:$ThueSuatTemp;
                    $TenGoi = $worksheet->getCellByColumnAndRow($ColTenGoi, $row)->getValue() . '';
                    $SanLuong = $worksheet->getCellByColumnAndRow($ColSanLuong, $row)->getValue();
                    $GiaTinhThue = $worksheet->getCellByColumnAndRow($ColGiaTinhThue, $row)->getValue();
                    $SoTien = $worksheet->getCellByColumnAndRow($ColSoTien, $row)->getValue();
                    
                    $nganhModel = new nganhModel($entityManager);
                    
                    $TiLeTinhThue = $nganhModel->getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue, $TieuMuc);
                    $dukienthuenam = new dukienthue();
                    // TNCN&GTGT
                    if ($TieuMuc == '1003' || $TieuMuc == '1701') {
                        if ($DoanhThuChiuThue * 12 > 100000000) {
                            $SoTien = intval($DoanhThuChiuThue * $TiLeTinhThue);
                        } else
                            $SoTien = 0;
                    } else 
                        if ($TieuMuc == '2601') // BVMT
                            $SoTien = intval($SanLuong * $GiaTinhThue);
                        else 
                            if ($TieuMuc == '3801') // TN
                                $SoTien = intval($SanLuong * $GiaTinhThue * $ThueSuat);
                            else 
                                if ($TieuMuc == '1757') // TTDB
                                    $SoTien = intval($GiaTinhThue * $ThueSuat);
                                else
                                    $SoTien = $post->get('SoTien');
                    
                    $nguoinopthue = $entityManager->find('Application\Entity\nguoinopthue', $MaSoThue);
                    $muclucngansach = $entityManager->find('Application\Entity\muclucngansach', $TieuMuc);
                    
                    $dukienthuenam->setKyThue($KyThue);
                    $dukienthuenam->setNguoinopthue($nguoinopthue);
                    $dukienthuenam->setMuclucngansach($muclucngansach);
                    $dukienthuenam->setDoanhThuChiuThue($DoanhThuChiuThue);
                    $dukienthuenam->setTiLeTinhThue($TiLeTinhThue);
                    $dukienthuenam->setThueSuat($ThueSuat);
                    
                    if ($TenGoi == "" && $TieuMuc != '2601' && $TieuMuc != '3801' && $TieuMuc != '1757')
                        $dukienthuenam->setTenGoi(null);
                    else
                        $dukienthuenam->setTenGoi($TenGoi);
                    
                    if ($SanLuong == 0 && $TieuMuc != '2601' && $TieuMuc != '3801')
                        $dukienthuenam->setSanLuong(null);
                    else
                        $dukienthuenam->setSanLuong($SanLuong);
                    
                    if ($GiaTinhThue == 0 && $TieuMuc != '2601' && $TieuMuc != '3801' && $TieuMuc != '1757')
                        $dukienthuenam->setGiaTinhThue(null);
                    else
                        $dukienthuenam->setGiaTinhThue($GiaTinhThue);
                    
                    $dukienthuenam->setSoTien($SoTien);
                    $dukienthuenam->setNgayPhaiNop(null);
                    $dukienthuenam->setTrangThai(0);
                    $dukienthuenam->setUser($user);
                    $entityManager->persist($dukienthuenam);
                    $dem++;
                }
            }
            $entityManager->flush();
            $entityManager->getConnection()->commit();
            $kq = new ketqua();
            $kq->setKq(true);
            $kq->setObj($KyThue);
            $kq->setMessenger("Import dữ liệu thành công, có $dem DỰ KIẾN THUẾ được thêm thành công !");
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