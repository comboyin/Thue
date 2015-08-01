<?php
namespace Xuatbaocao\Model;

use Application\Entity\ketqua;
use Application\base\baseModel;
use Application\Entity\user;
use Application\Entity\thongtinngungnghi;

class XuatbaocaoModel extends baseModel
{

    /**
     * danh bạ HKD
     *
     * @param user $user            
     * @return \Application\Entity\ketqua
     */
    public function QTrHKD01($user, $Nam)
    {
        $kq = new ketqua();
        if ($user->getLoaiUser() <= 3) {
            
            try {
                
                $qb = $this->em->createQueryBuilder();
                
                // HKD nghĩ kinh doanh
                $DQL_HKD_NghiKD = $this->em->createQueryBuilder()
                    ->select('thongtinngungnghi1.MaTTNgungNghi')
                    ->from('Application\Entity\thongtinngungnghi', 'thongtinngungnghi1')
                    ->join('thongtinngungnghi1.nguoinopthue', 'nguoinopthue1')
                    ->join('nguoinopthue1.usernnts', 'usernnts1')
                    ->join('usernnts1.user', 'user1')
                    ->Where('user1.parentUser = ?1')
                    ->andWhere('thongtinngungnghi1.DenNgay is null')
                    ->getDQL();
                
                // Danh sach ngưng nghĩ
                $DQL_TTNgungNghi = 'select thongtinngungnghids.MaTTNgungNghi
                        from Application\Entity\thongtinngungnghi thongtinngungnghids 
                        where thongtinngungnghids.nguoinopthue = nguoinopthue';
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
                    ->andWhere("thongtinngungnghis.MaTTNgungNghi in ($DQL_HKD_NghiKD) OR (usernnts.ThoiGianKetThuc is null and 
                                    thongtinnnt.ThoiGianKetThuc is null and 
                                (not exists(select thongtinngungnghi2 from Application\\Entity\\thongtinngungnghi thongtinngungnghi2
                                            where thongtinngungnghi2.nguoinopthue = nguoinopthue) 
                                            OR thongtinngungnghis.MaTTNgungNghi >= All($DQL_TTNgungNghi)))")
                    ->andWhere("nguoinopthue.ThoiDiemBDKD < '$Nam-12-30'")
                    ->andWhere("thuemonbais.Nam = $Nam and thuemonbais.TrangThai = 1")
                    ->setParameter(1, $user);
                
                $kqs = $qb->getQuery()->getArrayResult();
                
                // create file
                if (count($kqs) > 0) {
                    include_once './vendor/phpoffice/PHPExcel-1.8/Classes/PHPExcel.php';
                    $fileNameCreate = './data/filetmp/01QTr-HKD.xls';
                    $fileTemplate = './data/MauImport/01QTr-HKD.xls';
                    if (file_exists($fileTemplate)) {
                        
                        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                        $objPHPExcel = $objReader->load($fileTemplate);
                        
                        $IndexCucThue = 'A3';
                        $IndexChiCucThue = 'A4';
                        $IndexDoiThue = 'A5';
                        $IndexNam = 'A9';
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexChiCucThue, "CỤC THUẾ: TP.HỒ CHÍ MINH");
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexCucThue, 'CHI CỤC THUẾ: ' . $user->getCoquanthue()
                            ->getChicucthue()
                            ->getTenGoi());
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexDoiThue, 'ĐỘI THUẾ: ' . $user->getCoquanthue()
                            ->getTenGoi());
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexNam, "Năm: $Nam");
                        
                        $baseRow = 14;
                        
                        foreach ($kqs as $r => $dataRow) {
                            $row = $baseRow + $r;
                            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
                            
                            $objPHPExcel->getActiveSheet()
                                ->setCellValue('A' . $row, $r + 1)
                                ->setCellValue('B' . $row, $dataRow['MaSoThue'])
                                ->setCellValue('C' . $row, $dataRow['TenHKD'])
                                ->setCellValue('D' . $row, $dataRow['DiaChiKD'])
                                ->setCellValue('E' . $row, $dataRow['NgheKD'])
                                ->setCellValue('F' . $row, $dataRow['DoanhSo'])
                                ->setCellValue('G' . $row, $dataRow['ThoiDiemBDKD']->format('d-m-Y'))
                                ->setCellValue('H' . $row, $dataRow['TuNgay'] == null ? '' : $dataRow['DenNgay'] == null ? $dataRow['TuNgay']->format('d-m-Y') : '');
                        }
                        foreach ($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
                            $rd->setRowHeight(- 1);
                        }
                        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save($fileNameCreate);
                        if (file_exists($fileNameCreate)) {
                            $kq->setKq(true);
                            $kq->setMessenger('Tiến trình tạo report thành công !');
                            $kq->setObj($fileNameCreate);
                        }
                    } else {
                        $kq->setKq(false);
                        $kq->setMessenger('Không tìm thấy File mẫu !');
                    }
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger("Không tìm thấy hộ kinh doanh nào trong năm $Nam !");
                }
                
                return $kq;
            } catch (\Exception $e) {
                
                $kq->setKq(false);
                $kq->setMessenger($e->getMessage());
                return $kq;
            }
        } else {
            $kq->setKq(false);
            $kq->setMessenger("Bạn không có quyền sử dụng mẫu này !");
            return $kq;
        }
    }

    /**
     * danh bạ HKD
     *
     * @param user $user            
     * @return \Application\Entity\ketqua
     */
    public function QTrHKD10($user, $KyThue)
    {
        $kq = new ketqua();
        $Thang = explode("/", $KyThue)[0];
        $Nam = explode("/", $KyThue)[1];
        
        if ($user->getLoaiUser() <= 3) {
            
            try {
                
                $qb = $this->em->createQueryBuilder();
                
                // HKD nghĩ kinh doanh
                $DQL_HKD_NghiKD = $this->em->createQueryBuilder()
                    ->select('thongtinngungnghi1')
                    ->from('Application\Entity\thongtinngungnghi', 'thongtinngungnghi1')
                    ->join('thongtinngungnghi1.nguoinopthue', 'nguoinopthue1')
                    ->join('nguoinopthue1.usernnts', 'usernnts1')
                    ->join('usernnts1.user', 'user1')
                    ->Where('user1.parentUser = ?1')
                    ->andWhere('thongtinngungnghi1.DenNgay is null')
                    ->andWhere('nguoinopthue1 = nguoinopthue')
                    ->getDQL();
                
                // Danh sach ngưng nghĩ
                $DQL_TTNgungNghi = 'select thongtinngungnghids.MaTTNgungNghi
                        from Application\Entity\thongtinngungnghi thongtinngungnghids
                        where thongtinngungnghids.nguoinopthue = nguoinopthue';
                
                $qb->select(array(
                    'nguoinopthue.MaSoThue',
                    'nguoinopthue.TenHKD',
                    'thongtinnnt.DiaChiKD',
                    'nguoinopthue.NgheKD',
                    'thuemonbais.DoanhSo',
                    'nguoinopthue.ThoiDiemBDKD'
                ))
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->leftJoin('nguoinopthue.thongtinnnt', 'thongtinnnt')
                    ->leftJoin('nguoinopthue.NNTNganhs', 'NNTNganhs')
                    ->leftJoin('nguoinopthue.thongtinngungnghis', 'thongtinngungnghis')
                    ->leftJoin('nguoinopthue.thuemonbais', 'thuemonbais')
                    ->Where('user.parentUser = ?1')
                    ->andWhere("not exists ($DQL_HKD_NghiKD) and usernnts.ThoiGianKetThuc is null and 
                    thongtinnnt.ThoiGianKetThuc is null and NNTNganhs.ThoiGianKetThuc is null and 
                (not exists(select thongtinngungnghi2 from Application\\Entity\\thongtinngungnghi thongtinngungnghi2
                            where thongtinngungnghi2.nguoinopthue = nguoinopthue) 
                            OR thongtinngungnghis.MaTTNgungNghi >= All($DQL_TTNgungNghi))")
                    ->andWhere("nguoinopthue.ThoiDiemBDKD < '$Nam-$Thang-30'")
                    ->andWhere("thuemonbais.Nam = $Nam and thuemonbais.TrangThai = 1 and thuemonbais.DoanhSo*12 < 100000000")
                    ->setParameter(1, $user);
                
                $kqs = $qb->getQuery()->getArrayResult();
                
                // create file
                if (count($kqs) > 0) {
                    include_once './vendor/phpoffice/PHPExcel-1.8/Classes/PHPExcel.php';
                    $fileNameCreate = './data/filetmp/10QTr-HKD.xls';
                    $fileTemplate = './data/MauImport/10QTr-HKD.xls';
                    if (file_exists($fileTemplate)) {
                        
                        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                        $objPHPExcel = $objReader->load($fileTemplate);
                        
                        $IndexCucThue = 'A3';
                        $IndexChiCucThue = 'A4';
                        $IndexDoiThue = 'A5';
                        $IndexNam = 'A9';
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexChiCucThue, "CỤC THUẾ: TP.HỒ CHÍ MINH");
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexCucThue, 'CHI CỤC THUẾ: ' . $user->getCoquanthue()
                            ->getChicucthue()
                            ->getTenGoi());
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexDoiThue, 'ĐỘI THUẾ: ' . $user->getCoquanthue()
                            ->getTenGoi());
                        
                        $Thang = explode("/", $KyThue)[0];
                        $Nam = explode("/", $KyThue)[1];
                        $objPHPExcel->getActiveSheet()->

                        setCellValue($IndexNam, "Tháng $Thang năm $Nam");
                        
                        $baseRow = 13;
                        
                        foreach ($kqs as $r => $dataRow) {
                            $row = $baseRow + $r;
                            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
                            
                            $objPHPExcel->getActiveSheet()
                                ->setCellValue('A' . $row, $r + 1)
                                ->setCellValue('B' . $row, $dataRow['MaSoThue'])
                                ->setCellValue('C' . $row, $dataRow['TenHKD'])
                                ->setCellValue('D' . $row, $dataRow['DiaChiKD'])
                                ->setCellValue('E' . $row, $dataRow['NgheKD'])
                                ->setCellValue('F' . $row, $dataRow['DoanhSo'] * 12);
                        }
                        foreach ($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
                            $rd->setRowHeight(- 1);
                        }
                        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save($fileNameCreate);
                        if (file_exists($fileNameCreate)) {
                            $kq->setKq(true);
                            $kq->setMessenger('Tiến trình tạo report thành công !');
                            $kq->setObj($fileNameCreate);
                        }
                    } else {
                        $kq->setKq(false);
                        $kq->setMessenger('Không tìm thấy File mẫu !');
                    }
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger("Không tìm thấy hộ kinh doanh nào trong năm $Nam !");
                }
                
                return $kq;
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                $kq->setKq(false);
                $kq->setMessenger($e->getMessage());
                return $kq;
            }
        } else {
            
            $kq->setKq(false);
            $kq->setMessenger("Bạn không có quyền sử dụng mẫu này !");
            return $kq;
        }
    }

    /**
     * danh bạ HKD
     *
     * @param user $user            
     * @return \Application\Entity\ketqua
     */
    public function QTrHKD12($user, $KyThue)
    {
        $kq = new ketqua();
        $Thang = explode("/", $KyThue)[0];
        $Nam = explode("/", $KyThue)[1];
        
        if ($user->getLoaiUser() <= 3) {
            
            try {
                
                $qb = $this->em->createQueryBuilder();
                
                // HKD nghĩ kinh doanh
                $DQL_HKD_NghiKD = $this->em->createQueryBuilder()
                    ->select('thongtinngungnghi1')
                    ->from('Application\Entity\thongtinngungnghi', 'thongtinngungnghi1')
                    ->join('thongtinngungnghi1.nguoinopthue', 'nguoinopthue1')
                    ->join('nguoinopthue1.usernnts', 'usernnts1')
                    ->join('usernnts1.user', 'user1')
                    ->Where('user1.parentUser = ?1')
                    ->andWhere('thongtinngungnghi1.DenNgay is null')
                    ->andWhere('nguoinopthue1 = nguoinopthue')
                    ->getDQL();
                
                // Danh sach ngưng nghĩ
                $DQL_TTNgungNghi = 'select thongtinngungnghids.MaTTNgungNghi
                        from Application\Entity\thongtinngungnghi thongtinngungnghids
                        where thongtinngungnghids.nguoinopthue = nguoinopthue';
                
                $qb->select(array(
                    'nguoinopthue.MaSoThue',
                    'nguoinopthue.TenHKD',
                    'thongtinnnt.DiaChiKD',
                    'nguoinopthue.NgheKD',
                    'thuemonbais.DoanhSo',
                    'thongtinngungnghis.TuNgay',
                    'thongtinngungnghis.DenNgay',
                    'kythuemgs.SoTienMG',
                    'muclucngansach.TieuMuc',
                    'nguoinopthue.ThoiDiemBDKD'
                ))
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->leftJoin('nguoinopthue.thongtinnnt', 'thongtinnnt')
                    ->Join('nguoinopthue.thongtinngungnghis', 'thongtinngungnghis')
                    ->join('thongtinngungnghis.miengiamthue', 'miengiamthue')
                    ->join('miengiamthue.kythuemgs', 'kythuemgs')
                    ->join('kythuemgs.muclucngansach', 'muclucngansach')
                    ->leftJoin('nguoinopthue.thuemonbais', 'thuemonbais')
                    ->Where('user.parentUser = ?1')
                    ->andWhere('kythuemgs.KyThue = ?2')
                    ->andWhere("not exists ($DQL_HKD_NghiKD) and usernnts.ThoiGianKetThuc is null and
                    thongtinnnt.ThoiGianKetThuc is null and
                    thongtinngungnghis.MaTTNgungNghi >= All($DQL_TTNgungNghi)")
                    ->andWhere("thuemonbais.Nam = $Nam and thuemonbais.TrangThai = 1 ")
                    ->setParameter(1, $user)
                    ->setParameter(2, $KyThue);
                
                $kqs = $qb->getQuery()->getArrayResult();
                
                var_dump($kqs);
                // create file
                if (count($kqs) > 0) {
                    include_once './vendor/phpoffice/PHPExcel-1.8/Classes/PHPExcel.php';
                    $fileNameCreate = './data/filetmp/12QTr-HKD.xls';
                    $fileTemplate = './data/MauImport/12QTr-HKD.xls';
                    if (file_exists($fileTemplate)) {
                        
                        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                        $objPHPExcel = $objReader->load($fileTemplate);
                        
                        $IndexCucThue = 'A1';
                        $IndexChiCucThue = 'A2';
                        $IndexDoiThue = 'A3';
                        $IndexNam = 'A5';
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexChiCucThue, "CỤC THUẾ: TP.HỒ CHÍ MINH");
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexCucThue, 'CHI CỤC THUẾ: ' . $user->getCoquanthue()
                            ->getChicucthue()
                            ->getTenGoi());
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexDoiThue, 'ĐỘI THUẾ: ' . $user->getCoquanthue()
                            ->getTenGoi());
                        
                        $Thang = explode("/", $KyThue)[0];
                        $Nam = explode("/", $KyThue)[1];
                        $objPHPExcel->getActiveSheet()->setCellValue($IndexNam, "Tháng $Thang năm $Nam");
                        
                        $baseRow = 11;
                        $indexRow =0;
                        $flag = 0;
                        foreach ($kqs as $r => $dataRow) {
                            $row = $baseRow + $indexRow;
                            
                            
                            if ($flag == 0) {
                                $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
                                $objPHPExcel->getActiveSheet()
                                    ->setCellValue('A' . $row, $r + 1)
                                    ->setCellValue('B' . $row, $dataRow['MaSoThue'])
                                    ->setCellValue('C' . $row, $dataRow['TenHKD'])
                                    ->setCellValue('D' . $row, $dataRow['DiaChiKD'])
                                    ->setCellValue('E' . $row, $dataRow['NgheKD'])
                                    ->setCellValue('F' . $row, $dataRow['TuNgay']->format('d-m-Y'))
                                    ->setCellValue('G' . $row, $dataRow['DenNgay']->format('d-m-Y'));
                                switch ($dataRow['TieuMuc']) {
                                    case '1701':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('I' . $row, $dataRow['SoTien']);
                                        break;
                                    case '1003':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('J' . $row, $dataRow['SoTien']);
                                        break;
                                    case '1757':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('K' . $row, $dataRow['SoTien']);
                                        break;
                                    case '3801':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('L' . $row, $dataRow['SoTien']);
                                        break;
                                    case '2601':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('M' . $row, $dataRow['SoTien']);
                                        break;
                                }
                                $flag = $dataRow['MaSoThue'];
                            }
                            else if($flag==$dataRow['MaSoThue']){
                                switch ($dataRow['TieuMuc']) {
                                    case '1701':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('I' . $row, $dataRow['SoTien']);
                                        break;
                                    case '1003':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('J' . $row, $dataRow['SoTien']);
                                        break;
                                    case '1757':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('K' . $row, $dataRow['SoTien']);
                                        break;
                                    case '3801':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('L' . $row, $dataRow['SoTien']);
                                        break;
                                    case '2601':
                                        $objPHPExcel->getActiveSheet()
                                        ->setCellValue('M' . $row, $dataRow['SoTien']);
                                        break;
                                }
                            }else{
                                $flag=0;
                                $indexRow++;
                            }
                        }
                        foreach ($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
                            $rd->setRowHeight(- 1);
                        }
                        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                        $objWriter->save($fileNameCreate);
                        if (file_exists($fileNameCreate)) {
                            $kq->setKq(true);
                            $kq->setMessenger('Tiến trình tạo report thành công !');
                            $kq->setObj($fileNameCreate);
                        }
                    } else {
                        $kq->setKq(false);
                        $kq->setMessenger('Không tìm thấy File mẫu !');
                    }
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger("Không tìm thấy hộ kinh doanh nào trong năm $Nam !");
                }
                
                return $kq;
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                $kq->setKq(false);
                $kq->setMessenger($e->getMessage());
                return $kq;
            }
        } else {
            
            $kq->setKq(false);
            $kq->setMessenger("Bạn không có quyền sử dụng mẫu này !");
            return $kq;
        }
    }
}

?>