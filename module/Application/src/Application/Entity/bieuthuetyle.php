<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="bieuthuetyle",
 * indexes={
 * @ORM\Index(name="fk_bieuthuetyle_nganh1_idx", columns={"MaNganh"}),
 * @ORM\Index(name="fk_bieuthuetyle_muclucngansach1_idx", columns={"TieuMuc"})
 * }
 * )
 */
class bieuthuetyle
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $IDNganhMLNS;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="bieuthuetyles")
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     * 
     * @var muclucngansach
     */
    private $muclucngansach;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nganh", inversedBy="bieuthuetyles")
     * @ORM\JoinColumn(name="MaNganh", referencedColumnName="MaNganh", nullable=false, onDelete="restrict")
     */
    private $nganh;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiGianBatDau;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ThoiGianKetThuc;
    
    /**
     * @ORM\Column(type="float",nullable=false)
     */
    private $TyLeTinhThue;
 /**
     * @return the $IDNganhMLNS
     */
    public function getIDNganhMLNS()
    {
        return $this->IDNganhMLNS;
    }

 /**
     * @return the $muclucngansach
     */
    public function getMuclucngansach()
    {
        return $this->muclucngansach;
    }

 /**
     * @return the $nganh
     */
    public function getNganh()
    {
        return $this->nganh;
    }

 /**
     * @return the $ThoiGianBatDau
     */
    public function getThoiGianBatDau()
    {
        return $this->ThoiGianBatDau;
    }

 /**
     * @return the $ThoiGianKetThuc
     */
    public function getThoiGianKetThuc()
    {
        return $this->ThoiGianKetThuc;
    }

 /**
     * @return the $TiLeTinhThue
     */
    public function getTiLeTinhThue()
    {
        return $this->TiLeTinhThue;
    }

 /**
     * @param field_type $IDNganhMLNS
     */
    public function setIDNganhMLNS($IDNganhMLNS)
    {
        $this->IDNganhMLNS = $IDNganhMLNS;
    }

 /**
     * @param \Application\Entity\muclucngansach $muclucngansach
     */
    public function setMuclucngansach($muclucngansach)
    {
        $this->muclucngansach = $muclucngansach;
    }

 /**
     * @param field_type $nganh
     */
    public function setNganh($nganh)
    {
        $this->nganh = $nganh;
    }

 /**
     * @param field_type $ThoiGianBatDau
     */
    public function setThoiGianBatDau($ThoiGianBatDau)
    {
        $this->ThoiGianBatDau = $ThoiGianBatDau;
    }

 /**
     * @param field_type $ThoiGianKetThuc
     */
    public function setThoiGianKetThuc($ThoiGianKetThuc)
    {
        $this->ThoiGianKetThuc = $ThoiGianKetThuc;
    }

 /**
     * @param field_type $TiLeTinhThue
     */
    public function setTiLeTinhThue($TiLeTinhThue)
    {
        $this->TiLeTinhThue = $TiLeTinhThue;
    }

}