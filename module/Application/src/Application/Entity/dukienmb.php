<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="dukienmb",
 * indexes={
 * @ORM\Index(name="fk_dukienmb_nguoinopthue1_idx", columns={"MaSoThue"}),
 * @ORM\Index(name="fk_dukienmb_muclucngansach1_idx", columns={"Bac"})
 * }
 * )
 */
class dukienmb
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="dukienmbs")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
    


    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=4,nullable=false)
     */
    private $Nam;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="dukienmbs")
     * @ORM\JoinColumn(name="Bac", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $VonKD;

    /**
     * @ORM\Column(type="integer"),nullable=false, options={"default":"0"})
     */
    private $SoTien;
    
    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $NgayPhaiNop;
    
    /**
     * @ORM\Column(type="integer",length=1,nullable=false,options={"default":"0"})
     */
    private $TrangThai;
 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @return the $Nam
     */
    public function getNam()
    {
        return $this->Nam;
    }

 /**
     * @return the $muclucngansach
     */
    public function getMuclucngansach()
    {
        return $this->muclucngansach;
    }

 /**
     * @return the $VonKD
     */
    public function getVonKD()
    {
        return $this->VonKD;
    }

 /**
     * @return the $SoTien
     */
    public function getSoTien()
    {
        return $this->SoTien;
    }

 /**
     * @return the $NgayPhaiNop
     */
    public function getNgayPhaiNop()
    {
        return $this->NgayPhaiNop;
    }

 /**
     * @return the $TrangThai
     */
    public function getTrangThai()
    {
        return $this->TrangThai;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

 /**
     * @param field_type $Nam
     */
    public function setNam($Nam)
    {
        $this->Nam = $Nam;
    }

 /**
     * @param field_type $muclucngansach
     */
    public function setMuclucngansach($muclucngansach)
    {
        $this->muclucngansach = $muclucngansach;
    }

 /**
     * @param field_type $VonKD
     */
    public function setVonKD($VonKD)
    {
        $this->VonKD = $VonKD;
    }

 /**
     * @param field_type $SoTien
     */
    public function setSoTien($SoTien)
    {
        $this->SoTien = $SoTien;
    }

 /**
     * @param field_type $NgayPhaiNop
     */
    public function setNgayPhaiNop($NgayPhaiNop)
    {
        $this->NgayPhaiNop = $NgayPhaiNop;
    }

 /**
     * @param field_type $TrangThai
     */
    public function setTrangThai($TrangThai)
    {
        $this->TrangThai = $TrangThai;
    }

}