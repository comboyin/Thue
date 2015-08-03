<?php
namespace Quanlysothue\Excel;

use Application\base\baseModel;
use Application\Entity\BangKe;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\chitietbangke;
use Application\Entity\ketqua;
use Application\Entity\user;

class Xuatbangke extends baseModel
{

    /**
     *
     * @param unknown $dsMaSoThue            
     * @param unknown $KyThue            
     * @param user $user            
     * @return multitype:boolean string Ambigous <NULL, field_type>
     */
    public function createChungTu($dsMaSoThue, $KyThue, $user)
    {
        $Ngay = explode('/', $KyThue)[0];
        $Nam = explode('/', $KyThue)[1];
        $KyLapBo = "";
        if ($Ngay == "01") {
            $KyLapBo = "12" . "/" . ($Nam - 1);
        } else {
            $Ngay = $Ngay - 1;
            if (strlen($Ngay) == 1) {
                $Ngay = "0" . $Ngay;
            }
            $KyLapBo = $Ngay . '/' . $Nam;
        }
        
        $arrayBangKe = new ArrayCollection();
        
        foreach ($dsMaSoThue as $MaSoThue) {
            $phatsinh = $this->phatsinh($MaSoThue, $KyThue);
            if ($phatsinh != null) {
                $arrayBangKe->add($phatsinh);
                // nhieu bang ke no cua kỳ trước đó
                $arrayBangKeSono = $this->sono($MaSoThue, $KyLapBo);
                foreach ($arrayBangKeSono->getValues() as $array) {
                    $arrayBangKe->add($array);
                }
            }
        }
        if ($arrayBangKe->count() > 0) {
            
            include_once './vendor/phpoffice/PHPExcel-1.8/Classes/PHPExcel.php';
            $fileNameCreate = './data/filetmp/ChungTu_MAU.xls';
            $fileTemplate = './data/MauImport/ChungTu_MAU.xls';
            if (file_exists($fileTemplate)) {
                
                $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                $objPHPExcel = $objReader->load($fileTemplate);
                
                $IndexSoLoChungTu = 'A';
                $IndexNgayCT = 'B';
                $IndexNgayHT = 'C';
                $IndexMaSoThue = 'D';
                $IndexTenNNT = 'E';
                $IndexChuong = 'F';
                $IndexSoChungTu = 'G';
                $IndexTieuMuc = 'H';
                $IndexSoTien = 'I';
                $IndexTrangThiaLoChungTu = 'J';
                $IndexHuyChungTu = 'K';
                $IndexNamNganSach = 'L';
                $IndexNgayKBHachToan = 'M';
                $IndexKyHieuGiaoDich = 'N';
                $IndexTaiKhoan = 'O';
                $IndexCQT = 'P';
                
                $IndexCoQuanThue = 'Q';
                $IndexNguoiNhap = 'R';
                $IndexKhoBacNN = 'S';
                $IndexMaPhongQuanLy = 'T';
                $IndexMaCanBo = 'U';
                $IndexTinhChatKhoanNop = 'V';
                
                $baseRow = 2;
                $indexRow = 0;
                /* @var $dataRow BangKe */
                foreach ($arrayBangKe as $r => $dataRow) {
                    $chitiets = $dataRow->getChiTietBangKe()->getValues();
                    foreach ($chitiets as $chitiet) {
                        $row = $baseRow + $indexRow;
                        
                        /* @var $chitiet chitietbangke */
                        
                        //var_dump($dataRow->getChiTietBangKe()->count());
                        $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
                        $arrayNgayCTTemp = explode('-', $chitiet->getNgayCT());
                        $arrayNgayHTTemp = explode('-', $chitiet->getNgayHT());
                        
                        $excel_NgayCT   = \PHPExcel_Shared_Date::FormattedPHPToExcel($arrayNgayCTTemp[2], $arrayNgayCTTemp[1], $arrayNgayCTTemp[0]);
                        $excel_NgayHT= \PHPExcel_Shared_Date::FormattedPHPToExcel($arrayNgayHTTemp[2], $arrayNgayHTTemp[1], $arrayNgayHTTemp[0]);
                        
                        $objPHPExcel->getActiveSheet()
                            ->setCellValue($IndexSoLoChungTu . $row, $dataRow->getSoLoChungTu())
                            
                            ->setCellValue($IndexNgayCT . $row, $excel_NgayCT)
                            ->setCellValue($IndexNgayHT . $row, $excel_NgayHT)
                            ->setCellValue($IndexMaSoThue . $row, $dataRow->getMaSoThue())
                            ->setCellValue($IndexTenNNT . $row, $dataRow->getTenHKD())
                            ->setCellValue($IndexChuong . $row, '757')
                            ->setCellValue($IndexSoChungTu . $row, $dataRow->getSoChungTu())
                            ->setCellValue($IndexTieuMuc . $row, $chitiet->getTieuMuc())
                            ->setCellValue($IndexSoTien . $row, $chitiet->getSoTien())
                            ->setCellValue($IndexTrangThiaLoChungTu . $row, 'Đã hạch toán')
                            ->setCellValue($IndexHuyChungTu . $row, '')
                            ->setCellValue($IndexNamNganSach . $row, '01')
                            ->setCellValue($IndexNgayKBHachToan . $row, $excel_NgayHT)
                            ->setCellValue($IndexKyHieuGiaoDich . $row, 'C2')
                            ->setCellValue($IndexTaiKhoan . $row, '711')
                            ->setCellValue($IndexCQT . $row, '7906')
                            ->setCellValue($IndexCoQuanThue . $row, $user->getCoquanthue()
                            ->getChicucthue()
                            ->getTenGoi())
                            ->setCellValue($IndexNguoiNhap . $row, 'PHUONG03.HCM')
                            ->
                        setCellValue($IndexKhoBacNN . $row, '0114')
                            ->setCellValue($IndexMaPhongQuanLy . $row, $user->getCoquanthue()
                            ->getMaCoQuan())
                            ->setCellValue($IndexMaCanBo . $row, $user->getMaUser())
                            ->setCellValue($IndexTinhChatKhoanNop . $row, 'N');
                        
                        $indexRow++;
                    }
                }
                foreach ($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
                    $rd->setRowHeight(- 1);
                }
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save($fileNameCreate);
            }
            $kq = new ketqua();
            if (file_exists($fileNameCreate)) {
                $kq->setKq(true);
                $kq->setMessenger('Tiến trình tạo report thành công !');
                $kq->setObj($fileNameCreate);
            } else {
                
                $kq->setKq(false);
                $kq->setMessenger('Không tìm thấy file !');
            }
        } else {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger('Không tìm thấy bảng kê nào !');
        }
        
        return $kq->toArray();
    }

    public function dowloadBangKe($dsMaSoThue, $KyThue)
    {
        $Ngay = explode('/', $KyThue)[0];
        $Nam = explode('/', $KyThue)[1];
        $KyLapBo = "";
        if ($Ngay == "01") {
            $KyLapBo = "12" . "/" . ($Nam - 1);
        } else {
            $Ngay = $Ngay - 1;
            if (strlen($Ngay) == 1) {
                $Ngay = "0" . $Ngay;
            }
            $KyLapBo = $Ngay . '/' . $Nam;
        }
        
        $arrayBangKe = new ArrayCollection();
        
        foreach ($dsMaSoThue as $MaSoThue) {
            $phatsinh = $this->phatsinh($MaSoThue, $KyThue);
            if ($phatsinh != null && $phatsinh->getTongTien()>0) {
                $arrayBangKe->add($phatsinh);
                // nhieu bang ke no cua kỳ trước đó
                $arrayBangKeSono = $this->sono($MaSoThue, $KyLapBo);
                foreach ($arrayBangKeSono->getValues() as $array) {
                    $arrayBangKe->add($array);
                }
            }
        }
        if ($arrayBangKe->count() > 0) {
            $kq = new ketqua();
            $kq->setKq(true);
            $soluong = $arrayBangKe->count();
            $kq->setMessenger("Đã có $soluong bảng kê được tạo, file đóng gói đang được tạo, vui lòng chờ .............");
            $kq->setObj($this->TaoZipNhieuBangKe($arrayBangKe));
        } else {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger('Không tìm thấy bảng kê nào !');
        }
        
        return $kq->toArray();
    }

    private function ValidationXuatBanKe($dsMaSoThue, $KyThue)
    {}

    private function KiemTraSoThue($MaSoThue, $KyThue)
    {
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        $qb->select(array(
            'nguoinopthue.MaSoThue',
            'nguoinopthue.TenHKD',
            'thongtinnnt.DiaChiKD',
            'muclucngansach.TieuMuc',
            'muclucngansach.TenGoi',
            'thue.SoTien',
            'thue.KyThue'
        ))
            ->from('Application\Entity\thue', 'thue')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->join('thue.nguoinopthue', 'nguoinopthue')
            ->join('nguoinopthue.thongtinnnt', 'thongtinnnt')
            ->where('thongtinnnt.ThoiGianKetThuc is null')
            ->andWhere('thue.TrangThai = 1')
            ->andWhere('nguoinopthue.MaSoThue = ?1')
            ->andWhere('thue.KyThue = ?2')
            ->setParameter(1, $MaSoThue)
            ->setParameter(2, $KyThue);
        
        $kqs = $qb->getQuery()->getResult();
        if (count($kqs) == 0) {
            $kq->setKq(false);
            $kq->setMessenger("$MaSoThue trong kỳ thuế $KyThue chưa được lập sổ thuể !");
        } else {
            $kq->setKq(true);
        }
        
        return $kq;
    }

    /**
     *
     * @param string $MaSoThue            
     * @param string $KyThue            
     * @return BangKe|null
     *
     */
    public function phatsinh($MaSoThue, $KyThue)
    {
        $qb = $this->em->createQueryBuilder();
        
        $qb->select(array(
            'nguoinopthue.MaSoThue',
            'nguoinopthue.TenHKD',
            'thongtinnnt.DiaChiKD'
        ))
            ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
            ->join('nguoinopthue.thongtinnnt', 'thongtinnnt')
            ->where('thongtinnnt.ThoiGianKetThuc is null')
            ->andWhere('nguoinopthue.MaSoThue = ?1')
            ->setParameter(1, $MaSoThue);
        $kqs = $qb->getQuery()->getSingleResult();
        
        $BangKe = new BangKe();
        $BangKe->setSoChungTu();
        $BangKe->setMaSoThue($kqs['MaSoThue']);
        $BangKe->setTenHKD($kqs['TenHKD']);
        $BangKe->setDiaChiKD($kqs['DiaChiKD']);
        $BangKe->setKyThue($KyThue);
        
        // Thue
        $qbThue = $this->em->createQueryBuilder();
        $qbThue->select(array(
            'nguoinopthue.MaSoThue',
            'nguoinopthue.TenHKD',
            'thongtinnnt.DiaChiKD',
            'muclucngansach.TieuMuc',
            'muclucngansach.TenGoi',
            'thue.SoTien',
            'thue.KyThue',
            'thue.NgayPhaiNop'
        ))
            ->from('Application\Entity\thue', 'thue')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->join('thue.nguoinopthue', 'nguoinopthue')
            ->join('nguoinopthue.thongtinnnt', 'thongtinnnt')
            ->where('thongtinnnt.ThoiGianKetThuc is null')
            ->andWhere('thue.TrangThai = 1')
            ->andWhere('nguoinopthue.MaSoThue = ?1')
            ->andWhere('thue.KyThue = ?2')
            ->andWhere('thue.SoChungTu is null')
            ->setParameter(1, $MaSoThue)
            ->setParameter(2, $KyThue);
        
        $kqthue = $qbThue->getQuery()->getArrayResult();
        
        if (count($kqthue) > 0) {
            $chitietbangkes = new ArrayCollection();
            foreach ($kqthue as $kq) {
                
                $chitietbangke = new chitietbangke();
                $chitietbangke->setKyThue($kq['KyThue']);
                $chitietbangke->setNoiDung($kq['TenGoi']);
                $chitietbangke->setSoTien($kq['SoTien']);
                $chitietbangke->setTieuMuc($kq['TieuMuc']);
                $chitietbangke->setNgayHT($kq['NgayPhaiNop']->format('d-m-Y'));
                $chitietbangke->setNgayCT($BangKe->getKyThue());
                $BangKe->getChiTietBangKe()->add($chitietbangke);
            }
            $this->truythu($BangKe);
            $this->miengiam($BangKe);
        }
        
        $this->monbai($BangKe);
        return $BangKe;
    }

    /**
     *
     * @param BangKe $BangKe            
     *
     */
    private function truythu(&$BangKe)
    {
        $MaSoThue = $BangKe->getMaSoThue();
        $KyThue = $BangKe->getKyThue();
        
        $qb = $this->em->createQueryBuilder();
        $qb->select(array(
            'muclucngansach.TieuMuc',
            'muclucngansach.TenGoi',
            'truythu.SoTien',
            'thue.KyThue'
        ))
            ->from('Application\Entity\truythu', 'truythu')
            ->join('truythu.thue', 'thue')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->join('thue.nguoinopthue', 'nguoinopthue')
            ->andWhere('nguoinopthue.MaSoThue = ?1')
            ->andWhere('thue.KyThue = ?2')
            ->setParameter(1, $MaSoThue)
            ->setParameter(2, $KyThue);
        
        $kqs = $qb->getQuery()->getResult();
        
       
        foreach ($kqs as $key => $kq) {
            for ($i = 0; $i < $BangKe->getChiTietBangKe()->count(); $i ++) {
                /* @var $ChiTietBangKe chitietbangke */
                $ChiTietBangKe = $BangKe->getChiTietBangKe()->get($i);
                if ($ChiTietBangKe->getKyThue() == $kq['KyThue'] && $ChiTietBangKe->getTieuMuc() == $kq['TieuMuc']) {
                    $ChiTietBangKe->setSoTien($kq['SoTien'] + $ChiTietBangKe->getSoTien());
                   
                }
            }
        }

    }
    
    
    /**
     *
     * @param BangKe $BangKe
     *
     */
    private function miengiam(&$BangKe)
    {
        $MaSoThue = $BangKe->getMaSoThue();
        $KyThue = $BangKe->getKyThue();
    
        $qb = $this->em->createQueryBuilder();
        $qb->select(array(
            'muclucngansach.TieuMuc',
            'muclucngansach.TenGoi',
            'kythuemg.SoTienMG',
            'kythuemg.KyThue'
        ))
        ->from('Application\Entity\kythuemg', 'kythuemg')
        ->join('kythuemg.miengiamthue', 'miengiamthue')
        ->join('miengiamthue.nguoinopthue', 'nguoinopthue')
        ->join('kythuemg.muclucngansach', 'muclucngansach')
        ->Where('nguoinopthue.MaSoThue = ?1')
        ->andWhere('kythuemg.KyThue = ?2')
        ->setParameter(1, $MaSoThue)
        ->setParameter(2, $KyThue);
    
        $kqs = $qb->getQuery()->getResult();
    
        
        foreach ($kqs as $key => $kq) {
            
            foreach ($BangKe->getChiTietBangKe()->getValues() as $ChiTietBangKe){
                
                if ($ChiTietBangKe->getKyThue() == $kq['KyThue'] && $ChiTietBangKe->getTieuMuc() == $kq['TieuMuc']) {
                    $ChiTietBangKe->setSoTien($ChiTietBangKe->getSoTien() - $kq['SoTienMG']  );
                   
                }
                
                
            }
        }
    }
    

    private function monbai(&$BangKe)
    {
        $MaSoThue = $BangKe->getMaSoThue();
        $Nam = explode('/', $BangKe->getKyThue())[1];
        $qb = $this->em->createQueryBuilder()
            ->select(array(
            'muclucngansach.TieuMuc',
            'muclucngansach.TenGoi',
            'thuemonbai.SoTien',
            'thuemonbai.Nam',
            'thuemonbai.NgayPhaiNop'
        ))
            ->from('Application\Entity\thuemonbai', 'thuemonbai')
            ->join('thuemonbai.nguoinopthue', 'nguoinopthue')
            ->join('thuemonbai.muclucngansach', 'muclucngansach')
            ->where('not exists (select chitietchungtu.SoTien from Application\Entity\chitietchungtu 
                            chitietchungtu join chitietchungtu.muclucngansach muclucngansach1
                             where chitietchungtu.KyThue = ?3 
                            and muclucngansach1.TieuMuc = muclucngansach.TieuMuc)')
            ->andWhere('thuemonbai.Nam = ?1')
            ->andWhere('nguoinopthue.MaSoThue = ?2')
            ->andWhere('thuemonbai.SoChungTu is null')
            ->setParameter(3, '01/' . $Nam)
            ->setParameter(1, $Nam)
            ->setParameter(2, $MaSoThue);
        
        $kqs = $qb->getQuery()->getResult();
        foreach ($kqs as $kq) {
            // add chitietbangke vao bang ke
            foreach ($kqs as $key => $kq) {
                
                $ChiTietBangKeNew = new chitietbangke();
                $ChiTietBangKeNew->setKyThue($kq['Nam']);
                $ChiTietBangKeNew->setNoiDung($kq['TenGoi']);
                $ChiTietBangKeNew->setSoTien($kq['SoTien']);
                $ChiTietBangKeNew->setTieuMuc($kq['TieuMuc']);
                $ChiTietBangKeNew->setNgayHT($kq['NgayPhaiNop']->format('d-m-Y'));
                $ChiTietBangKeNew->setNgayCT($BangKe->getKyThue());
                $BangKe->getChiTietBangKe()->add($ChiTietBangKeNew);
            }
        }
    }

    /**
     */
    public function sono($MaSoThue, $KyLapBo)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select(array(
            'nguoinopthue.MaSoThue',
            'nguoinopthue.TenHKD',
            'thongtinnnt.DiaChiKD',
            'sono.KyThue',
            'sono.SoTien',
            'muclucngansach.TieuMuc',
            'muclucngansach.TenGoi'
        ))
            ->from('Application\Entity\sono', 'sono')
            ->join('sono.nguoinopthue', 'nguoinopthue')
            ->join('sono.muclucngansach', 'muclucngansach')
            ->join('nguoinopthue.thongtinnnt', 'thongtinnnt')
            ->where('thongtinnnt.ThoiGianKetThuc is null')
            ->andWhere('sono.KyLapBo = ?1')
            ->andWhere('nguoinopthue.MaSoThue = ?2')
            ->setParameter(1, $KyLapBo)
            ->setParameter(2, $MaSoThue);
        
        $kqs = $qb->getQuery()->getResult();
        
        $BangKes = new ArrayCollection();
        
        $i = 0;
        while ($i < count($kqs)) {
            $KyThue = $kqs[$i]['KyThue'];
            $j = 0;
            $BK_MaSoThue = $kqs[0]['MaSoThue'];
            $BangKe = new BangKe();
            $BangKe->setMaSoThue($kqs[0]['MaSoThue']);
            $BangKe->setTenHKD($kqs[0]['TenHKD']);
            $BangKe->setDiaChiKD($kqs[0]['DiaChiKD']);
            $BangKe->setKyThue($KyThue);
            
            while ($j < count($kqs)) {
                if ($kqs[$j]['KyThue'] == $KyThue) {
                    $ct_KyThue = $kqs[$j]['KyThue'];
                    $ct_TenGoi = $kqs[$j]['TenGoi'];
                    $ct_SoTien = $kqs[$j]['SoTien'];
                    $ct_TieuMuc = $kqs[$j]['TieuMuc'];
                    // them chi tiet bang ke
                    $chitietbangke = new chitietbangke();
                    $chitietbangke->setKyThue($ct_KyThue);
                    $chitietbangke->setNoiDung($ct_TenGoi);
                    $chitietbangke->setSoTien($ct_SoTien);
                    $chitietbangke->setTieuMuc($ct_TieuMuc);
                    $qb_ct = $this->em->createQueryBuilder()->select('thue.NgayPhaiNop')
                        ->from('Application\Entity\thue', 'thue')
                        ->join('thue.nguoinopthue', 'nguoinopthue')
                        ->join('thue.muclucngansach', 'muclucngansach')
                        ->where('muclucngansach.TieuMuc = ?1')
                        ->andWhere('nguoinopthue.MaSoThue = ?2')
                        ->andWhere('thue.KyThue = ?3')
                        ->setParameter(1, $ct_TieuMuc)
                        ->setParameter(2, $BK_MaSoThue)
                        ->setParameter(3, $ct_KyThue);
                    $qb_ct->getQuery()->getSingleResult();
                    $NgayHT = $qb_ct['NgayPhaiNop']->format('d-m-Y');
                    $chitietbangke->setNgayHT($NgayHT);
                    $chitietbangke->setNgayCT($BangKe->getKyThue());
                    $BangKe->getChiTietBangKe()->add($chitietbangke);
                    unset($kqs[$j]);
                    $kqs = array_values($kqs);
                } else {
                    $j ++;
                }
            }
            $BangKes->add($BangKe);
        }
        
        return $BangKes;
    }

    /**
     *
     * @param ArrayCollection $dsBangKe            
     * @return array
     */
    public function CreateMultiBangKe($dsBangKe)
    {
        $dsFileBangKe = [];
        
        $Bangkes = $dsBangKe->getValues();
        /* @var $Bangke BangKe */
        foreach ($Bangkes as $Bangke) {
            array_push($dsFileBangKe, $this->CreateOneBangKe($Bangke));
        }
        
        $this->ZipBangKe($dsFileBangKe);
    }

    /**
     *
     * @param BangKe $bangke            
     * @return string
     */
    public function CreateOneBangKe($bangke)
    {
        $NameDirResources = './data/MauImport/01BKNT_A5.docx';
        $NameDirResults = './data/filetmp/';
        
        $today = date("d-m-Y");
        
        // tao ten thu muc
        if (! file_exists($NameDirResults . $today)) {
            mkdir($NameDirResults . $today, 0777, true);
        }
        
        return $this->mailMergeBangKe($bangke, $NameDirResources, $NameDirResults . $today . '/');
    }

    /**
     *
     * @param ArrayCollection $DanhSachBangKe            
     */
    public function TaoZipNhieuBangKe($DanhSachBangKe)
    {
        $FileNames = array();
        
        foreach ($DanhSachBangKe->getValues() as $row) {
            
            array_push($FileNames, $this->CreateOneBangKe($row));
        }
        
        return $this->ZipBangKe($FileNames);
    }

    /**
     * return file name
     *
     * @param BangKe $bangke            
     * @param string $fileMau            
     * @param string $NameDirResults            
     * @return string
     */
    private function mailMergeBangKe($bangke, $fileMau, $NameDirResults)
    {
        include_once 'vendor/phpoffice/PhpWord/TemplateProcessor.php';
        include_once 'vendor/phpoffice/PhpWord/Settings.php';
        include_once 'vendor/phpoffice/PhpWord/Shared/ZipArchive.php';
        include_once 'vendor/phpoffice/PhpWord/Shared/String.php';
        
        /* @var $bangke BangKe */
        
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($fileMau);
        
        // Thong tin HKD
        $templateProcessor->setValue('maSoThue', $bangke->getMaSoThue());
        $templateProcessor->setValue('tenNguoiNopThue', $bangke->getTenHKD());
        $templateProcessor->setValue('diaChi', $bangke->getDiaChiKD());
        
        // chi tiet
        $templateProcessor->cloneRow('STT', $bangke->getChiTietBangKe()
            ->count());
        $count = 1;
        $kythue = $bangke->getKythue();
        /* @var $chitietbangkes chitietbangke */
        $chitietbangkes = $bangke->getChiTietBangKe()->getValues();
        foreach ($chitietbangkes as $row) {
            
            $templateProcessor->setValue('STT#' . $count, $count);
            $templateProcessor->setValue('noiDung#' . $count, $row->getNoiDung());
            $templateProcessor->setValue('maNDKT#' . $count, $row->getTieuMuc());
            
            $templateProcessor->setValue('kyThue#' . $count, $row->getKyThue());
            
            $templateProcessor->setValue('soTien#' . $count, number_format($row->getSoTien()));
            
            $count ++;
        }
        
        $templateProcessor->setValue('Thang', explode('-', (new \DateTime())->format('d-m-Y'))[1]);
        $templateProcessor->setValue('Nam', explode('-', (new \DateTime())->format('d-m-Y'))[2]);
        
        $templateProcessor->setValue('tongTien', number_format($bangke->getTongTien()));
        $templateProcessor->setValue('soTienBangChu', $bangke->SoTienBangChu());
        
        // tạo file
        $timeNow = time();
        $fileNameSaveAs = $bangke->getMaSoThue() . '_' . str_replace('/', '-', $bangke->getKyThue()) . '_' . $timeNow . uniqid() . '.docx';
        $templateProcessor->saveAs($NameDirResults . $fileNameSaveAs);
        
        return $NameDirResults . $fileNameSaveAs;
    }

    /**
     *
     * @param array $FileName            
     */
    function ZipBangKe($FileNames = array())
    {
        error_reporting(0);
        // Checking files are selected
        $zip = new \ZipArchive(); // Load zip library
        $zip_name = time() . ".zip"; // Zip name
        if ($zip->open($zip_name, \ZipArchive::CREATE) !== TRUE) {
            // Opening zip file to load files
            echo "* Sorry ZIP creation failed at this time";
        }
        foreach ($FileNames as $fileName) {
            
            $zip->addFile($fileName, pathinfo($fileName)['basename']); // Adding files into zip
        }
        
        $zip->close();
        
        /*
         * if (file_exists($zip_name) && $fd = fopen($zip_name, "r")) {
         *
         * $fileSize = filesize($zip_name);
         * // push to download the zip
         * header('Content-type: application/zip');
         *
         * header("Content-disposition: attachment; filename=\"" . $zip_name . "\"");
         *
         * header("Content-length: $fileSize");
         * header("Cache-control: private"); // use this to open files directly
         *
         * ob_clean();
         * // set_time_limit(0);
         *
         * // readfile($zip_name);
         * while (! feof($fd)) {
         * $buffer = fread($fd, 2048);
         * echo $buffer;
         * // remove zip file is exists in temp path
         * }
         *
         * fclose($fd);
         *
         * unlink($zip_name);
         * exit();
         * }
         */
        return $zip_name;
    }
}

?>