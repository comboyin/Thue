<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="thongtinngungnghi",
 *     indexes={
 *         @ORM\Index(name="fk_thongtinngungnghi_nguoinopthue1_idx", columns={"MaSoThue"})
 *     }
 * )
 */
class thongtinngungnghi
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $MaTTNgungNghi;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $TuNgay;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $DenNgay;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $LyDo;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $NgayNopDon;


    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\miengiamthue", mappedBy="thongtinngungnghi")
     */
    private $miengiamthue;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="thongtinngungnghis")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
 /**
     * @param field_type $MaTTNgungNghi
     */
    public function setMaTTNgungNghi($MaTTNgungNghi)
    {
        $this->MaTTNgungNghi = $MaTTNgungNghi;
    }

 /**
     * @return the $MaTTNgungNghi
     */
    public function getMaTTNgungNghi()
    {
        return $this->MaTTNgungNghi;
    }

 /**
     * @return the $TuNgay
     */
    public function getTuNgay()
    {
        return $this->TuNgay==null? null:$this->TuNgay->format("d-m-Y");
        
    }

 /**
     * @return the $DenNgay
     */
    public function getDenNgay()
    {
        return $this->DenNgay==null? null:$this->DenNgay->format("d-m-Y");
        
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
         return $this->NgayNopDon==null? null:$this->NgayNopDon->format("d-m-Y");
        
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
     * @param field_type $MaTTNgungNghi
     */
    public function setIDngungnghi($MaTTNgungNghi)
    {
        $this->MaTTNgungNghi = $MaTTNgungNghi;
    }

 /**
     * @param field_type $TuNgay
     */
    public function setTuNgay($TuNgay)
    {
        $this->TuNgay = \DateTime::createFromFormat('Y-m-d', $TuNgay);
    }

 /**
     * @param field_type $DenNgay
     */
    public function setDenNgay($DenNgay)
    {
        $this->DenNgay =$DenNgay==null?null: \DateTime::createFromFormat('Y-m-d', $DenNgay);
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
        $this->NgayNopDon = \DateTime::createFromFormat('Y-m-d', $NgayNopDon);
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