<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
class BangKe
{
    private $MaSoThue;
    private $TenHKD;
    private $DiaChiKD;
    private $TongTien;
    private $NgayThang;
    private $KyThue;
    
    
    
    private $ChiTietBangKe;
    
    
    /**
     * @return the $KyThue
     */
    public function getKyThue()
    {
        return $this->KyThue;
    }

 /**
     * @param field_type $KyThue
     */
    public function setKyThue($KyThue)
    {
        $this->KyThue = $KyThue;
    }

 /**
     * @return the $NgayThang
     */
    public function getNgayThang()
    {
        return $this->NgayThang;
    }

 /**
     * @param field_type $NgayThang
     */
    public function setNgayThang($NgayThang)
    {
        $this->NgayThang = $NgayThang;
    }

    public function __construct(){
        $this->ChiTietBangKe = new ArrayCollection();
        $this->TongTien=0;
    }
    /**
     * @return the $MaSoThue
     */
    public function getMaSoThue()
    {
        return $this->MaSoThue;
    }

     /**
     * @return the $TenHKD
     */
    public function getTenHKD()
    {
        return $this->TenHKD;
    }

     /**
     * @return the $DiaChiKD
     */
    public function getDiaChiKD()
    {
        return $this->DiaChiKD;
    }

 /**
     * @return the $TongTien
     */
    public function getTongTien()
    {
        $this->TongTien=0;
        for ($i=0;$i<$this->getChiTietBangKe()->count();$i++){
            $this->TongTien+=$this->getChiTietBangKe()->get($i)->getSoTien();
        }
        return $this->TongTien;
    }

 /**
     * @return ArrayCollection
     */
    public function getChiTietBangKe()
    {
        return $this->ChiTietBangKe;
    }

 /**
     * @param field_type $MaSoThue
     */
    public function setMaSoThue($MaSoThue)
    {
        $this->MaSoThue = $MaSoThue;
    }

 /**
     * @param field_type $TenHKD
     */
    public function setTenHKD($TenHKD)
    {
        $this->TenHKD = $TenHKD;
    }

 /**
     * @param field_type $DiaChiKD
     */
    public function setDiaChiKD($DiaChiKD)
    {
        $this->DiaChiKD = $DiaChiKD;
    }

 /**
     * @param field_type $TongTien
     */
    public function setTongTien($TongTien)
    {
        $this->TongTien = $TongTien;
    }

 /**
     * @param \Doctrine\Common\Collections\ArrayCollection $ChiTietBangKe
     */
    public function setChiTietBangKe($ChiTietBangKe)
    {
        $this->ChiTietBangKe = $ChiTietBangKe;
    }
    
    

    public function SoTienBangChu()
    {
        $this->getTongTien();
        $amount = $this->TongTien;
        if ($amount <= 0) {
            return $textnumber = "";
        }
        $Text = array(
            "không",
            "một",
            "hai",
            "ba",
            "bốn",
            "năm",
            "sáu",
            "bảy",
            "tám",
            "chín"
        );
        $TextLuythua = array(
            "",
            "nghìn",
            "triệu",
            "tỷ",
            "ngàn tỷ",
            "triệu tỷ",
            "tỷ tỷ"
        );
        $textnumber = "";
        $length = strlen($amount);
    
        for ($i = 0; $i < $length; $i ++)
            $unread[$i] = 0;
    
            for ($i = 0; $i < $length; $i ++) {
            $so = substr($amount, $length - $i - 1, 1);
    
            if (($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)) {
            for ($j = $i + 1; $j < $length; $j ++) {
            $so1 = substr($amount, $length - $j - 1, 1);
            if ($so1 != 0)
                break;
            }
    
            if (intval(($j - $i) / 3) > 0) {
            for ($k = $i; $k < intval(($j - $i) / 3) * 3 + $i; $k ++)
                $unread[$k] = 1;
                }
                }
                }
    
                for ($i = 0; $i < $length; $i ++) {
                $so = substr($amount, $length - $i - 1, 1);
                if ($unread[$i] == 1)
                    continue;
    
                    if (($i % 3 == 0) && ($i > 0))
                        $textnumber = $TextLuythua[$i / 3] . " " . $textnumber;
    
                        if ($i % 3 == 2)
                            $textnumber = 'trăm ' . $textnumber;
    
                        if ($i % 3 == 1)
                            $textnumber = 'mươi ' . $textnumber;
    
                        $textnumber = $Text[$so] . " " . $textnumber;
                }
    
                    // Phai de cac ham replace theo dung thu tu nhu the nay
                    $textnumber = str_replace("không mươi", "lẻ", $textnumber);
                    $textnumber = str_replace("lẻ không", "", $textnumber);
                    $textnumber = str_replace("mươi không", "mươi", $textnumber);
                    $textnumber = str_replace("một mươi", "mười", $textnumber);
                    $textnumber = str_replace("mươi năm", "mươi lăm", $textnumber);
                    $textnumber = str_replace("mươi một", "mươi mốt", $textnumber);
                    $textnumber = str_replace("mười năm", "mười lăm", $textnumber);
    
                    return ucfirst($textnumber . " đồng chẵn");
    }
    
    
    
    
}

?>