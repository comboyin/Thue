<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="muclucngansach")
 */
class muclucngansach
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20)
     */
    private $TieuMuc;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $TenGoi;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\chitietchungtu", mappedBy="muclucngansach")
     */
    private $chitietchungtus;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\kythuemg", mappedBy="muclucngansach")
     */
    private $kythuemgs;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\sono", mappedBy="muclucngansach")
     */
    private $sonos;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\thue", mappedBy="muclucngansach")
     */
    private $thues;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\thuemonbai", mappedBy="muclucngansach")
     */
    private $thuemonbais;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukienthue", mappedBy="muclucngansach")
     */
    private $dukienthues;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukientruythu", mappedBy="muclucngansach")
     */
    private $dukientruythus;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\truythu", mappedBy="muclucngansach")
     */
    private $truythus;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukienmb", mappedBy="muclucngansach")
     */
    private $dukienmbs;
 /**
     * @return the $TieuMuc
     */
    public function getTieuMuc()
    {
        return $this->TieuMuc;
    }

 /**
     * @return the $TenGoi
     */
    public function getTenGoi()
    {
        return $this->TenGoi;
    }

 /**
     * @return the $chitietchungtus
     */
    public function getChitietchungtus()
    {
        return $this->chitietchungtus;
    }

 /**
     * @return the $kythuemgs
     */
    public function getKythuemgs()
    {
        return $this->kythuemgs;
    }

 /**
     * @return the $sonos
     */
    public function getSonos()
    {
        return $this->sonos;
    }

 /**
     * @return the $thues
     */
    public function getThues()
    {
        return $this->thues;
    }

 /**
     * @return the $thuemonbais
     */
    public function getThuemonbais()
    {
        return $this->thuemonbais;
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
     * @param field_type $TieuMuc
     */
    public function setTieuMuc($TieuMuc)
    {
        $this->TieuMuc = $TieuMuc;
    }

 /**
     * @param field_type $TenGoi
     */
    public function setTenGoi($TenGoi)
    {
        $this->TenGoi = $TenGoi;
    }

 /**
     * @param field_type $chitietchungtus
     */
    public function setChitietchungtus($chitietchungtus)
    {
        $this->chitietchungtus = $chitietchungtus;
    }

 /**
     * @param field_type $kythuemgs
     */
    public function setKythuemgs($kythuemgs)
    {
        $this->kythuemgs = $kythuemgs;
    }

 /**
     * @param field_type $sonos
     */
    public function setSonos($sonos)
    {
        $this->sonos = $sonos;
    }

 /**
     * @param field_type $thues
     */
    public function setThues($thues)
    {
        $this->thues = $thues;
    }

 /**
     * @param field_type $thuemonbais
     */
    public function setThuemonbais($thuemonbais)
    {
        $this->thuemonbais = $thuemonbais;
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