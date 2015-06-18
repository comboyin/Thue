<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="thuemonbai",
 *     indexes={
 *         @ORM\Index(name="fk_thuemonbai_muclucngansach1_idx", columns={"Bac"}),
 *         @ORM\Index(name="fk_thuemonbai_nguoinopthue1_idx", columns={"MaSoThue"})
 *     }
 * )
 */
class thuemonbai
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=4)
     */
    private $Nam;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $VonKD;

    /**
     * @ORM\Column(type="integer",nullable=false, options={"default":"0"})
     */
    private $SoTien;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $NgayPhaiNop;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false)
     */
    private $KetSo;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $SoChungTu;


    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="thuemonbais")
     * @ORM\JoinColumn(name="Bac", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="thuemonbais")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
 /**
     * @return the $Nam
     */
    public function getNam()
    {
        return $this->Nam;
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
     * @return the $KetSo
     */
    public function getKetSo()
    {
        return $this->KetSo;
    }

 /**
     * @return the $SoChungTu
     */
    public function getSoChungTu()
    {
        return $this->SoChungTu;
    }

 /**
     * @return the $muclucngansach
     */
    public function getMuclucngansach()
    {
        return $this->muclucngansach;
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @param field_type $Nam
     */
    public function setNam($Nam)
    {
        $this->Nam = $Nam;
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
     * @param field_type $KetSo
     */
    public function setKetSo($KetSo)
    {
        $this->KetSo = $KetSo;
    }

 /**
     * @param field_type $SoChungTu
     */
    public function setSoChungTu($SoChungTu)
    {
        $this->SoChungTu = $SoChungTu;
    }

 /**
     * @param field_type $muclucngansach
     */
    public function setMuclucngansach($muclucngansach)
    {
        $this->muclucngansach = $muclucngansach;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

}