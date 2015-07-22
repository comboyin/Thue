<?php
namespace Application\Entity;

class chitietbangke
{
    private $TieuMuc;
    private $NoiDung;
    private $SoTien;
    private $KyThue;
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