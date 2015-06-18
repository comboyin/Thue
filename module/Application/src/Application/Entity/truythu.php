<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="truythu",
 * indexes={
 * @ORM\Index(name="fk_truythu_nguoinopthue1_idx", columns={"MaSoThue"}),
 * @ORM\Index(name="fk_truythu_muclucngansach1_idx", columns={"TieuMuc"})
 * }
 * )
 */
class truythu
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10)
     */
    private $KyThue;

    /**
     * @ORM\Column(type="integer",nullable=false, options={"default":"0"})
     */
    private $SoTien;
    
    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $DoanhSo;
    
    /**
     * @ORM\Column(type="float",nullable=false, options={"default":"1"})
     */
    private $TiLeTinhThue;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $LyDo;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="truythus")
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="truythus")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
 /**
     * @return the $KyThue
     */
    public function getKyThue()
    {
        return $this->KyThue;
    }

 /**
     * @return the $SoTien
     */
    public function getSoTien()
    {
        return $this->SoTien;
    }

 /**
     * @return the $DoanhSo
     */
    public function getDoanhSo()
    {
        return $this->DoanhSo;
    }

 /**
     * @return the $TiLeTinhThue
     */
    public function getTiLeTinhThue()
    {
        return $this->TiLeTinhThue;
    }

 /**
     * @return the $LyDo
     */
    public function getLyDo()
    {
        return $this->LyDo;
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
     * @param field_type $KyThue
     */
    public function setKyThue($KyThue)
    {
        $this->KyThue = $KyThue;
    }

 /**
     * @param field_type $SoTien
     */
    public function setSoTien($SoTien)
    {
        $this->SoTien = $SoTien;
    }

 /**
     * @param field_type $DoanhSo
     */
    public function setDoanhSo($DoanhSo)
    {
        $this->DoanhSo = $DoanhSo;
    }

 /**
     * @param field_type $TiLeTinhThue
     */
    public function setTiLeTinhThue($TiLeTinhThue)
    {
        $this->TiLeTinhThue = $TiLeTinhThue;
    }

 /**
     * @param field_type $LyDo
     */
    public function setLyDo($LyDo)
    {
        $this->LyDo = $LyDo;
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