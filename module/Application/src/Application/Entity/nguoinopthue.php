<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="nguoinopthue",
 * uniqueConstraints={
 * @ORM\UniqueConstraint(name="SoCMND", columns={"SoCMND"}),
 * @ORM\UniqueConstraint(name="SoGPKD", columns={"SoGPKD"})
 * }
 * )
 */
class nguoinopthue
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20)
     */
    private $MaSoThue;

    /**
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $TenHKD;
    
    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $SoCMND;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $DiaChiCT;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $SoGPKD;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $NgayCapMST;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiDiemBDKD;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $NgheKD;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\chungtu", mappedBy="nguoinopthue")
     */
    private $chungtus;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\thongtinnnt", mappedBy="nguoinopthue")
     */
    private $thongtinnnt;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\thue", mappedBy="nguoinopthue")
     */
    private $thues;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\miengiamthue", mappedBy="nguoinopthue")
     */
    private $miengiamthue;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\ngungnghi", mappedBy="nguoinopthue")
     */
    private $ngungnghis;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\sono", mappedBy="nguoinopthue")
     */
    private $sonos;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\thuemonbai", mappedBy="nguoinopthue")
     */
    private $thuemonbais;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\usernnt", mappedBy="nguoinopthue")
     */
    private $usernnts;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\NNTNganh", mappedBy="nguoinopthue")
     */
    private $NNTNganhs;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukienthue", mappedBy="nguoinopthue")
     */
    private $dukienthues;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukientruythu", mappedBy="nguoinopthue")
     */
    private $dukientruythus;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\truythu", mappedBy="nguoinopthue")
     */
    private $truythus;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukienmb", mappedBy="nguoinopthue")
     */
    private $dukienmbs;
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
     * @return the $SoCMND
     */
    public function getSoCMND()
    {
        return $this->SoCMND;
    }

 /**
     * @return the $DiaChiCT
     */
    public function getDiaChiCT()
    {
        return $this->DiaChiCT;
    }

 /**
     * @return the $SoGPKD
     */
    public function getSoGPKD()
    {
        return $this->SoGPKD;
    }

 /**
  * format d/m/Y
     * @return string
     */
    public function getNgayCapMST()
    {
        return $this->NgayCapMST->format('d/m/Y');
    }

 /**
     * @return string
     */
    public function getThoiDiemBDKD()
    {
        return $this->ThoiDiemBDKD->format("d/m/Y");
    }

 /**
     * @return the $NgheKD
     */
    public function getNgheKD()
    {
        return $this->NgheKD;
    }

 /**
     * @return the $chungtus
     */
    public function getChungtus()
    {
        return $this->chungtus;
    }

 /**
     * @return thongtinnnt
     */
    public function getThongtinnnt()
    {
        return $this->thongtinnnt;
    }

 /**
     * @return the $thues
     */
    public function getThues()
    {
        return $this->thues;
    }

 /**
     * @return the $miengiamthue
     */
    public function getMiengiamthue()
    {
        return $this->miengiamthue;
    }

 /**
     * @return the $ngungnghis
     */
    public function getNgungnghis()
    {
        return $this->ngungnghis;
    }

 /**
     * @return the $sonos
     */
    public function getSonos()
    {
        return $this->sonos;
    }

 /**
     * @return the $thuemonbais
     */
    public function getThuemonbais()
    {
        return $this->thuemonbais;
    }

 /**
     * @return usernnt
     */
    public function getUsernnts()
    {
        return $this->usernnts;
    }

 /**
     * @return NNTNganh[]
     */
    public function getNNTNganhs()
    {
        return $this->NNTNganhs;
    }

 /**
     * @return the $dukienthues
     */
    public function getDukienthues()
    {
        return $this->dukienthues;
    }

 /**
     * @return the $dukientruythus
     */
    public function getDukientruythus()
    {
        return $this->dukientruythus;
    }

 /**
     * @return the $truythus
     */
    public function getTruythus()
    {
        return $this->truythus;
    }

 /**
     * @return the $dukienmbs
     */
    public function getDukienmbs()
    {
        return $this->dukienmbs;
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
     * @param field_type $SoCMND
     */
    public function setSoCMND($SoCMND)
    {
        $this->SoCMND = $SoCMND;
    }

 /**
     * @param field_type $DiaChiCT
     */
    public function setDiaChiCT($DiaChiCT)
    {
        $this->DiaChiCT = $DiaChiCT;
    }

 /**
     * @param field_type $SoGPKD
     */
    public function setSoGPKD($SoGPKD)
    {
        $this->SoGPKD = $SoGPKD;
    }

 /**
     * @param field_type $NgayCapMST
     */
    public function setNgayCapMST($NgayCapMST)
    {
        $this->NgayCapMST = $NgayCapMST;
    }

 /**
     * @param field_type $ThoiDiemBDKD
     */
    public function setThoiDiemBDKD($ThoiDiemBDKD)
    {
        $this->ThoiDiemBDKD = $ThoiDiemBDKD;
    }

 /**
     * @param field_type $NgheKD
     */
    public function setNgheKD($NgheKD)
    {
        $this->NgheKD = $NgheKD;
    }

 /**
     * @param field_type $chungtus
     */
    public function setChungtus($chungtus)
    {
        $this->chungtus = $chungtus;
    }

 /**
     * @param field_type $thongtinnnt
     */
    public function setThongtinnnt($thongtinnnt)
    {
        $this->thongtinnnt = $thongtinnnt;
    }

 /**
     * @param field_type $thues
     */
    public function setThues($thues)
    {
        $this->thues = $thues;
    }

 /**
     * @param field_type $miengiamthue
     */
    public function setMiengiamthue($miengiamthue)
    {
        $this->miengiamthue = $miengiamthue;
    }

 /**
     * @param field_type $ngungnghis
     */
    public function setNgungnghis($ngungnghis)
    {
        $this->ngungnghis = $ngungnghis;
    }

 /**
     * @param field_type $sonos
     */
    public function setSonos($sonos)
    {
        $this->sonos = $sonos;
    }

 /**
     * @param field_type $thuemonbais
     */
    public function setThuemonbais($thuemonbais)
    {
        $this->thuemonbais = $thuemonbais;
    }

 /**
     * @param field_type $usernnts
     */
    public function setUsernnts($usernnts)
    {
        $this->usernnts = $usernnts;
    }

 /**
     * @param field_type $NNTNganhs
     */
    public function setNNTNganhs($NNTNganhs)
    {
        $this->NNTNganhs = $NNTNganhs;
    }

 /**
     * @param field_type $dukienthues
     */
    public function setDukienthues($dukienthues)
    {
        $this->dukienthues = $dukienthues;
    }

 /**
     * @param field_type $dukientruythus
     */
    public function setDukientruythus($dukientruythus)
    {
        $this->dukientruythus = $dukientruythus;
    }

 /**
     * @param field_type $truythus
     */
    public function setTruythus($truythus)
    {
        $this->truythus = $truythus;
    }

 /**
     * @param field_type $dukienmbs
     */
    public function setDukienmbs($dukienmbs)
    {
        $this->dukienmbs = $dukienmbs;
    }






}