<?php
namespace Xuatbaocao\Model;

use Application\Entity\ketqua;
use Application\base\baseModel;
use Application\Entity\user;

class XuatbaocaoModel extends baseModel
{

    /**
     *
     * @param user $user            
     * @return \Application\Entity\ketqua
     */
    public function QTrHKD01($user)
    {
        if ($user->getLoaiUser() == 3) {
            $kq = new ketqua();
            try {
                
                $qb = $this->em->createQueryBuilder();
                
                
                
                // Danh sách cán bộ viên của thuộc quản lý của đội trưởng
                $DQL_CanBoVien =  $this->em->createQueryBuilder()
                ->select("canbovien")
                ->from('Application\Entity\user', 'canbovien')
                ->where('canbovien.parentUser = ?1')
                ->getDQL();
                
                
                $DQL_HKD_NghiKD = $this->em->createQueryBuilder()
                ->select('nguoinopthue1')
                ->from('Application\Entity\nguoinopthue', 'nguoinopthue1')
                ->leftjoin('nguoinopthue1.thongtinngungnghis', 'thongtinngungnghis1')
                ->join('nguoinopthue1.usernnts', 'usernnts1')
                ->join('usernnts1.user', 'user1')
                ->andWhere('user1.parentUser = ?1')
                ->andWhere('thongtinngungnghis1.DenNgay is null')
                ->getDQL();
                
                
                $qb->select(array(
                    'nguoinopthue.MaSoThue',
                    'nguoinopthue.TenHKD',
                    'thongtinnnt.DiaChiKD',
                    'nguoinopthue.NgheKD',
                    'thuemonbais.DoanhSo',
                    'nguoinopthue.ThoiDiemBDKD',
                    'thongtinngungnghis.TuNgay',
                    'thongtinngungnghis.DenNgay'
                    
                ))
                ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->join('usernnts.user', 'user')
                ->leftJoin('nguoinopthue.thongtinnnt', 'thongtinnnt')
                ->leftJoin('nguoinopthue.thongtinngungnghis', 'thongtinngungnghis')
                ->leftJoin('nguoinopthue.thuemonbais', 'thuemonbais')
                ->andWhere('user.parentUser = ?1')
                ->where("usernnts.ThoiGianKetThuc is null OR nguoinopthue in ($DQL_HKD_NghiKD)")
                ->andwhere("user in ($DQL_CanBoVien)")
                ->setParameter(1, $user);
                
                
                
                
                
                
                
                
                
                
                
               /*  
                $qb->select(array(
                    'nguoinopthue.MaSoThue',
                    'nguoinopthue.TenHKD',
                    'thongtinnnt.DiaChiKD',
                    'nguoinopthue.NgheKD',
                    'thuemonbais.DoanhSo',
                    'nguoinopthue.ThoiDiemBDKD',
                    'thongtinngungnghis.TuNgay',
                    'thongtinngungnghis.DenNgay'
                ))
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->leftJoin('nguoinopthue.thongtinnnt', 'thongtinnnt')
                    ->leftJoin('nguoinopthue.thongtinngungnghis', 'thongtinngungnghis')
                    ->leftJoin('nguoinopthue.thuemonbais', 'thuemonbais')
                    ->Where('user.parentUser = ?1')
                    ->setParameter(1, $user); */
                
                $kqs = $qb->getQuery()->getArrayResult();
                
                // create file
                if (count($kqs) > 0) {
                    include_once './vendor/phpoffice/PHPExcel-1.8/Classes/PHPExcel.php';
                    $fileNameCreate = './data/filetmp/01Qtr-HKD.xls';
                    $fileTemplate = './data/MauImport/01Qtr-HKD.xls';
                    if (file_exists($fileTemplate)) {
                        $baseRow = 14;
                        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                        $objPHPExcel = $objReader->load($fileTemplate);
                        $a = 10;
                        foreach($kqs as $r => $dataRow) {
                            $row = $baseRow + $r;
                            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
                        
                            $objPHPExcel->getActiveSheet()
                            ->setCellValue('A'.$row, $r+1)
                            ->setCellValue('B'.$row, $dataRow['MaSoThue'])
                            ->setCellValue('C'.$row, $dataRow['TenHKD'])
                            ->setCellValue('D'.$row, $dataRow['DiaChiKD'])
                            ->setCellValue('E'.$row, $dataRow['NgheKD'])
                            ->setCellValue('F'.$row, $dataRow['DoanhSo'])
                            ->setCellValue('G'.$row,  $dataRow['ThoiDiemBDKD']->format('d-m-Y'))
                            ->setCellValue('H'.$row, $dataRow['TuNgay']==null?'': $dataRow['DenNgay']==null?$dataRow['TuNgay']->format('d-m-Y'):'');
                            
                        }
                        foreach ($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
                            $rd->setRowHeight(- 1);
                        }
                        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save($fileNameCreate);
                        if(file_exists($fileNameCreate)){
                            $kq->setKq(true);
                            $kq->setMessenger('Tiến trình tạo report thành công !');
                            $kq->setObj($fileNameCreate);
                        }
                        
                    } else {
                        $kq->setKq(false);
                        $kq->setMessenger('Không tìm thấy File mẫu !');
                    }
                }

                return $kq;
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                $kq->setKq(false);
                $kq->setMessenger($e->getMessage());
                return $kq;
            }
        }
    }
}

?>