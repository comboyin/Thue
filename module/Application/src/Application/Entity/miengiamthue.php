<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="miengiamthue",
 *     indexes={@ORM\Index(name="fk_miengiamthue_nguoinopthue1_idx", columns={"MaSoThue"})}
 * )
 */
class miengiamthue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     */
    private $SoQDMG;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $NgayNopDon;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $TuNgay;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $DenNgay;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $NoiDung;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $LyDo;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\ngungnghi", mappedBy="miengiamthue")
     */
    private $ngungnghi;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\kythuemg", mappedBy="miengiamthue")
     */
    private $kythuemgs;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="miengiamthue")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
 /**
     * @return the $SoQDMG
     */
    public function getSoQDMG()
    {
        return $this->SoQDMG;
    }

 /**
     * @return the $NgayNopDon
     */
    public function getNgayNopDon()
    {
        return $this->NgayNopDon;
    }

 /**
     * @return the $TuNgay
     */
    public function getTuNgay()
    {
        return $this->TuNgay;
    }

 /**
     * @return the $DenNgay
     */
    public function getDenNgay()
    {
        return $this->DenNgay;
    }

 /**
     * @return the $NoiDung
     */
    public function getNoiDung()
    {
        return $this->NoiDung;
    }

 /**
     * @return the $LyDo
     */
    public function getLyDo()
    {
        return $this->LyDo;
    }

 /**
     * @return the $ngungnghi
     */
    public function getNgungnghi()
    {
        return $this->ngungnghi;
    }

 /**
     * @return the $kythuemgs
     */
    public function getKythuemgs()
    {
        return $this->kythuemgs;
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @param field_type $SoQDMG
     */
    public function setSoQDMG($SoQDMG)
    {
        $this->SoQDMG = $SoQDMG;
    }

 /**
     * @param field_type $NgayNopDon
     */
    public function setNgayNopDon($NgayNopDon)
    {
        $this->NgayNopDon = $NgayNopDon;
    }

 /**
     * @param field_type $TuNgay
     */
    public function setTuNgay($TuNgay)
    {
        $this->TuNgay = $TuNgay;
    }

 /**
     * @param field_type $DenNgay
     */
    public function setDenNgay($DenNgay)
    {
        $this->DenNgay = $DenNgay;
    }

 /**
     * @param field_type $NoiDung
     */
    public function setNoiDung($NoiDung)
    {
        $this->NoiDung = $NoiDung;
    }

 /**
     * @param field_type $LyDo
     */
    public function setLyDo($LyDo)
    {
        $this->LyDo = $LyDo;
    }

 /**
     * @param field_type $ngungnghi
     */
    public function setNgungnghi($ngungnghi)
    {
        $this->ngungnghi = $ngungnghi;
    }

 /**
     * @param field_type $kythuemgs
     */
    public function setKythuemgs($kythuemgs)
    {
        $this->kythuemgs = $kythuemgs;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

}