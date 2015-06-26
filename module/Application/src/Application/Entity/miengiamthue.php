<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="miengiamthue",
 *     indexes={
 *     @ORM\Index(name="fk_miengiamthue_nguoinopthue1_idx", columns={"MaSoThue"}),
 *     @ORM\Index(name="fk_miengiamthue_thongtinngungnghi1_idx", columns={"SoQDMG"})
 *     }
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $LyDo;

    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\thongtinngungnghi", inversedBy="miengiamthue")
     * @ORM\JoinColumn(name="MaTTNgungNghi", referencedColumnName="MaTTNgungNghi", nullable=true, unique=true, onDelete="restrict")
     */
    private $thongtinngungnghi;

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
     * @return the $thongtinngungnghi
     */
    public function getThongtinngungnghi()
    {
        return $this->thongtinngungnghi;
    }

 /**
     * @param field_type $thongtinngungnghi
     */
    public function setThongtinngungnghi($thongtinngungnghi)
    {
        $this->thongtinngungnghi = $thongtinngungnghi;
    }

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
     * @return the $LyDo
     */
    public function getLyDo()
    {
        return $this->LyDo;
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
     * @param field_type $LyDo
     */
    public function setLyDo($LyDo)
    {
        $this->LyDo = $LyDo;
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