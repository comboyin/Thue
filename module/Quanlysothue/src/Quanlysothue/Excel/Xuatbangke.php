<?php
namespace Quanlysothue\Excel;

use Application\base\baseModel;
use Application\Entity\BangKe;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\chitietbangke;
use Application\Entity\ketqua;

class Xuatbangke extends baseModel
{

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
        
        foreach ($dsMaSoThue as $MaSoThue){
            $arrayBangKe->add($this->phatsinh($MaSoThue, $KyThue));
            
            $arrayBangKeSono = $this->sono($MaSoThue, $KyLapBo);
            foreach ($arrayBangKeSono->getValues() as $array){
                $arrayBangKe->add($array);
            }
            
        }
        
        $kq=new ketqua();
        $kq->setKq(true);
        $kq->setObj($this->TaoZipNhieuBangKe($arrayBangKe));
        
        return $kq->toArray();
        
        
    }

    /**
     *
     * @param string $MaSoThue            
     * @param string $KyThue            
     * @return BangKe
     *
     */
    public function phatsinh($MaSoThue, $KyThue)
    {
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
            ->andWhere('nguoinopthue.MaSoThue = ?1')
            ->andWhere('thue.KyThue = ?2')
            ->setParameter(1, $MaSoThue)
            ->setParameter(2, $KyThue);
        
        $kqs = $qb->getQuery()->getResult();
        
        $BangKe = new BangKe();
        $BangKe->setMaSoThue($kqs[0]['MaSoThue']);
        $BangKe->setTenHKD($kqs[0]['TenHKD']);
        $BangKe->setDiaChiKD($kqs[0]['DiaChiKD']);
        $BangKe->setKyThue($KyThue);
        
        $chitietbangkes = new ArrayCollection();
        foreach ($kqs as $kq) {
            $chitietbangke = new chitietbangke();
            $chitietbangke->setKyThue($kq['KyThue']);
            $chitietbangke->setNoiDung($kq['TenGoi']);
            $chitietbangke->setSoTien($kq['SoTien']);
            $chitietbangke->setTieuMuc($kq['TieuMuc']);
            $BangKe->getChiTietBangKe()->add($chitietbangke);
        }
        $this->truythu($BangKe);
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
            'truythu.KyThue'
        ))
            ->from('Application\Entity\truythu', 'truythu')
            ->join('truythu.muclucngansach', 'muclucngansach')
            ->join('truythu.nguoinopthue', 'nguoinopthue')
            ->andWhere('nguoinopthue.MaSoThue = ?1')
            ->andWhere('truythu.KyThue = ?2')
            ->setParameter(1, $MaSoThue)
            ->setParameter(2, $KyThue);
        
        $kqs = $qb->getQuery()->getResult();
        
        $temp = [];
        foreach ($kqs as $key => $kq) {
            for ($i = 0; $i < $BangKe->getChiTietBangKe()->count(); $i ++) {
                /* @var $ChiTietBangKe chitietbangke */
                $ChiTietBangKe = $BangKe->getChiTietBangKe()->get($i);
                if ($ChiTietBangKe->getKyThue() == $kq['KyThue'] && $ChiTietBangKe->getTieuMuc() == $kq['TieuMuc']) {
                    $ChiTietBangKe->setSoTien($kq['SoTien'] + $ChiTietBangKe->getSoTien());
                    array_push($temp, $key);
                }
            }
        }
        
        // Xóa những truy thu trùng với phát sinh
        foreach ($temp as $value) {
            unset($kqs[$value]);
        }
        
        // add chitietbangke vao bang ke
        foreach ($kqs as $key => $kq) {
            
            $ChiTietBangKeNew = new chitietbangke();
            $ChiTietBangKeNew->setKyThue($kq['KyThue']);
            $ChiTietBangKeNew->setNoiDung($kq['TenGoi']);
            $ChiTietBangKeNew->setSoTien($kq['SoTien']);
            $ChiTietBangKeNew->setTieuMuc($kq['TieuMuc']);
            
            $BangKe->getChiTietBangKe()->add($ChiTietBangKeNew);
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
            'thuemonbai.Nam'
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
            $BangKe = new BangKe();
            $BangKe->setMaSoThue($kqs[0]['MaSoThue']);
            $BangKe->setTenHKD($kqs[0]['TenHKD']);
            $BangKe->setDiaChiKD($kqs[0]['DiaChiKD']);
            $BangKe->setKyThue($KyThue);
            
            while ($j < count($kqs)) {
                if ($kqs[$j]['KyThue'] == $KyThue) {
                    
                    // them chi tiet bang ke
                    $chitietbangke = new chitietbangke();
                    $chitietbangke->setKyThue($kqs[$j]['KyThue']);
                    $chitietbangke->setNoiDung($kqs[$j]['TenGoi']);
                    $chitietbangke->setSoTien($kqs[$j]['SoTien']);
                    $chitietbangke->setTieuMuc($kqs[$j]['TieuMuc']);
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
        
        /* if (file_exists($zip_name) && $fd = fopen($zip_name, "r")) {
            
            $fileSize = filesize($zip_name);
            // push to download the zip
            header('Content-type: application/zip');
            
            header("Content-disposition: attachment; filename=\"" . $zip_name . "\"");
            
            header("Content-length: $fileSize");
            header("Cache-control: private"); // use this to open files directly
            
            ob_clean();
            // set_time_limit(0);
            
            // readfile($zip_name);
            while (! feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
                // remove zip file is exists in temp path
            }
            
            fclose($fd);
            
            unlink($zip_name);
            exit();
        }
 */
        return $zip_name;
    }
}

?>