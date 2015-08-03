<?php
namespace Application\Entity;

class chitietbangke
{
    private $TieuMuc;
    private $NoiDung;
    private $SoTien;
    private $KyThue;
    /**
     * 
     * @var string  */
    private $NgayCT;
    /**
     * 
     * @var string  */
    private $NgayHT;
    
    
 /**
     * @return the $NgayCT
     */
    public function getNgayCT()
    {
        return $this->NgayCT;
    }

 /**
     * @return the $NgayHT
     */
    public function getNgayHT()
    {
        return $this->NgayHT;
    }

     /**
      *  Ngày đi nộp
      *  tạo random ngày đi nộp
     * @param 
     */
    public function setNgayCT($KyThue)
    {
        $NgayPhaiNop = explode('-',$this->NgayHT)[0];
        
        $random = rand(4, $NgayPhaiNop-1);
        if(strlen($random)==1){
            $random = '0'.$random;
        }
        
        $Thang = explode('/', $KyThue)[0];
        $Nam =explode('/', $KyThue)[1];
        
        $this->NgayCT = $random.'-'.$Thang.'-'.$Nam;
    }

 /**
     * @param field_type $NgayHT
     */
    public function setNgayHT($NgayHT)
    {
        $this->NgayHT = $NgayHT;
    }

 /**
     * @return the $TieuMuc
     */
    public function getTieuMuc()
    {
        return $this->TieuMuc;
    }

 /**
     * @return the $NoiDung
     */
    public function getNoiDung()
    {
        return $this->NoiDung;
    }

 /**
     * @return the $SoTien
     */
    public function getSoTien()
    {
        return $this->SoTien;
    }

 /**
     * @return the $KyThue
     */
    public function getKyThue()
    {
        return $this->KyThue;
    }

 /**
     * @param field_type $TieuMuc
     */
    public function setTieuMuc($TieuMuc)
    {
        $this->TieuMuc = $TieuMuc;
    }

 /**
     * @param field_type $NoiDung
     */
    public function setNoiDung($NoiDung)
    {
        $this->NoiDung = $NoiDung;
    }

 /**
     * @param field_type $SoTien
     */
    public function setSoTien($SoTien)
    {
        $this->SoTien = $SoTien;
    }

 /**
     * @param field_type $KyThue
     */
    public function setKyThue($KyThue)
    {
        $this->KyThue = $KyThue;
    }

    
    
    
}

?>