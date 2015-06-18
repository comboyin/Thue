<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="ngungnghi",
 *     indexes={
 *         @ORM\Index(name="fk_ngungnghi_nguoinopthue1_idx", columns={"MaSoThue"}),
 *         @ORM\Index(name="fk_ngungnghi_miengiamthue1_idx", columns={"SoQDMG"})
 *     }
 * )
 */
class ngungnghi
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $IDngungnghi;

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
    private $LyDo;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $NgayNopDon;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\miengiamthue", inversedBy="ngungnghi")
     * @ORM\JoinColumn(name="SoQDMG", referencedColumnName="SoQDMG", nullable=false, unique=true, onDelete="restrict")
     */
    private $miengiamthue;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="ngungnghis")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
 /**
     * @return the $IDngungnghi
     */
    public function getIDngungnghi()
    {
        return $this->IDngungnghi;
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
     * @return the $LyDo
     */
    public function getLyDo()
    {
        return $this->LyDo;
    }

 /**
     * @return the $NgayNopDon
     */
    public function getNgayNopDon()
    {
        return $this->NgayNopDon;
    }

 /**
     * @return the $miengiamthue
     */
    public function getMiengiamthue()
    {
        return $this->miengiamthue;
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @param field_type $IDngungnghi
     */
    public function setIDngungnghi($IDngungnghi)
    {
        $this->IDngungnghi = $IDngungnghi;
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
     * @param field_type $LyDo
     */
    public function setLyDo($LyDo)
    {
        $this->LyDo = $LyDo;
    }

 /**
     * @param field_type $NgayNopDon
     */
    public function setNgayNopDon($NgayNopDon)
    {
        $this->NgayNopDon = $NgayNopDon;
    }

 /**
     * @param field_type $miengiamthue
     */
    public function setMiengiamthue($miengiamthue)
    {
        $this->miengiamthue = $miengiamthue;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

}