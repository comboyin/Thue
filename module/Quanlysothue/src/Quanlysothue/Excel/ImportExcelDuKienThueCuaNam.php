<?php
namespace Quanlysothue\Excel;


use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\dukientruythu;
use Application\Entity\nguoinopthue;
use Application\Entity\muclucngansach;
use Doctrine\ORM\EntityManager;
use Application\base\baseExcel;
class ImportExcelDuKienTruyThu extends baseExcel
{

    /**
     * @param EntityManager $EntityManager
     * @param string $fileName  */
    public function CheckFileImport($fileName,$EntityManager){
        $boolErr = 0;
        $messKeyExist  = "Doanh số này đã được dự kiến !";
        $messMaSoThueNotExist  = "Mã số thuế không tồn tại !";
        $messTieuMucNotExist  = "Tiểu mục không tồn tại !";
        $ColMaSoThue = 1;
        $ColTenHKD = 2;
        $ColTieuMuc = 3;
        $ColDoanhSo = 4;
        $ColTiLeTinhThue = 5;
        $ColLyDo = 6;
        
        $objPHPExcel =  \PHPExcel_IOFactory::load($fileName);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            /* $nrColumns = ord($highestColumn) - 64;
             echo "<br>The worksheet ".$worksheetTitle." has ";
             echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
             echo ' and ' . $highestRow . ' row.';
            echo '<br>Data: <table border="1"><tr>'; */
        
            $tempStr = $worksheet->getCellByColumnAndRow(0, 1)->getValue();
            $KyThue =  trim(substr($tempStr,strripos($tempStr, '-')+1));
        
             
            
        
        
            for ($row = 5; $row <= $highestRow; ++ $row) {
        
                $arrayMessErro = array();
                $MaSoThue = $worksheet->getCellByColumnAndRow($ColMaSoThue, $row)->getValue().'';
                $TieuMuc= $worksheet->getCellByColumnAndRow($ColTieuMuc, $row)->getValue().'';
                $DoanhSo = $worksheet->getCellByColumnAndRow($ColDoanhSo, $row)->getValue();
                $TiLeTinhThue = $worksheet->getCellByColumnAndRow($ColTiLeTinhThue, $row)->getValue();
                $SoTien = $DoanhSo*$TiLeTinhThue;
                $TrangThai = 0;
                $LyDo = $worksheet->getCellByColumnAndRow($ColLyDo, $row)->getValue();
                
                
                // check tieumuc
                $checkTieuMuc = $EntityManager->find('Application\Entity\muclucngansach', $TieuMuc);
                if($checkTieuMuc==null){
                    $arrayMessErro[] = $messTieuMucNotExist;
                }
                // check masothue
                $checkMaSoThue = $EntityManager->find('Application\Entity\nguoinopthue', $MaSoThue);
                if($checkMaSoThue==null){
                    $arrayMessErro[] = $messMaSoThueNotExist;
                }
                // check key
                if($checkTieuMuc!=null && $checkMaSoThue!=null){
                    $checkKey = $EntityManager->find('Application\Entity\dukientruythu', array(
                        'KyThue'=>$KyThue,
                        'nguoinopthue'=>$checkMaSoThue,
                        'muclucngansach'=>$checkTieuMuc
                        
                    ));
                    if($checkKey!=null){
                        $arrayMessErro[] = $messKeyExist;
                    }
                }
                
                
                if(count($arrayMessErro)>0){
                    $boolErr = 1;
                    // fill color row
                    $colFist = \PHPExcel_Cell::stringFromColumnIndex(0);
                    $colLast = \PHPExcel_Cell::stringFromColumnIndex($ColLyDo);
                    $strCellsFill = $colFist.$row.':'.$colLast.$row;
                    $this->cellColor($strCellsFill, 'F28A8C',$objPHPExcel);
                    $LastCol = $ColLyDo+1;
                    foreach ($arrayMessErro as $messerr){
                        
                        // add values
                        $worksheet->setCellValueByColumnAndRow($LastCol,$row,$messerr);
                        $LastCol++;
                    }
                    
                    
                    //create file and save file excel
                    
                    $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                    $fileName = './data/filetmp/'.'error-'.(new \DateTime())->format("d-m-Y").'-'.mt_rand().'.xlsx';
                    
                    $objWriter->save($fileName);
                    //return namefile
                    
                    
                }
               
            }
             
        }
        if($boolErr==1){
            return $fileName;
        }
        
        return false;
        
    }
    
    /**
     * Đọc dữ liệu từ file excel và thêm vào arrayconllection 
     * @param string $fileName
     * @return \Doctrine\Common\Collections\ArrayCollection  */
    public function PersitToArrayCollection($fileName){
        $arrayCollection = new ArrayCollection();
        $objPHPExcel =  \PHPExcel_IOFactory::load($fileName);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $worksheetTitle     = $worksheet->getTitle();
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            /* $nrColumns = ord($highestColumn) - 64;
            echo "<br>The worksheet ".$worksheetTitle." has ";
            echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
            echo ' and ' . $highestRow . ' row.';
            echo '<br>Data: <table border="1"><tr>'; */
    
            $tempStr = $worksheet->getCellByColumnAndRow(0, 1)->getValue();
            $KyThue =  trim(substr($tempStr,strripos($tempStr, '-')+1));
            
           
            
            
            
            for ($row = 5; $row <= $highestRow; ++ $row) {
                
                //echo $worksheet->getCellByColumnAndRow(2, 1)->getValue();
                    //$cell = $worksheet->getCellByColumnAndRow(2, $row);
                   // echo $cell->getValue();
                    //$dataType = \PHPExcel_Cell_DataType::dataTypeForValue($val);
                    $MaSoThue = $worksheet->getCellByColumnAndRow(1, $row)->getValue().'';
                    $TieuMuc= $worksheet->getCellByColumnAndRow(3, $row)->getValue().'';
                    $DoanhSo = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $TiLeTinhThue = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $SoTien = $DoanhSo*$TiLeTinhThue;
                    $TrangThai = 0;
                    $LyDo = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    // new dukientruythu
                    $dukientruythuTemp = new dukientruythu();
                    
                    //set nguoinopthue
                    $nguoinopthue = new nguoinopthue();
                    $nguoinopthue->setMaSoThue($MaSoThue);
                    $dukientruythuTemp->setNguoinopthue($nguoinopthue);
                    //set muclucngansach
                    $muclucngansach = new muclucngansach();
                    $muclucngansach->setTieuMuc($TieuMuc);
                    $dukientruythuTemp->setMuclucngansach($muclucngansach);
                    
                    //set kythue
                    $dukientruythuTemp->setKyThue($KyThue);
                    
                    
                    $dukientruythuTemp->setDoanhSo($DoanhSo);
                    $dukientruythuTemp->setTiLeTinhThue($TiLeTinhThue);
                    $dukientruythuTemp->setSoTien($SoTien);
                    $dukientruythuTemp->setTrangThai($TrangThai);
                    $dukientruythuTemp->setLyDo($LyDo);
                    
                    //add vao array
                    
                    $arrayCollection->add($dukientruythuTemp);
                    
                
                
            }
           
        }
        
        return $arrayCollection;
    }
}

?>