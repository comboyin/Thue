<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="chungtu", indexes={@ORM\Index(name="fk_chungtu_nguoinopthue1_idx", columns={"MaSoThue"})})
 */
class chungtu
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=50)
     */
    private $SoChungTu;



    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $NgayChungTu;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\chitietchungtu", mappedBy="chungtu")
     */
    private $chitietchungtus;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="chungtus")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
 /**
     * @return the $SoChungTu
     */
    public function getSoChungTu()
    {
        return $this->SoChungTu;
    }

 /**
     * @return the $LoaiChungTu
     */
    public function getLoaiChungTu()
    {
        return $this->LoaiChungTu;
    }

 /**
     * @return the $NgayChungTu
     */
    public function getNgayChungTu()
    {
        return $this->NgayChungTu;
    }



 /**
     * @return the $chitietchungtus
     */
    public function getChitietchungtus()
    {
        return $this->chitietchungtus;
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @param field_type $SoChungTu
     */
    public function setSoChungTu($SoChungTu)
    {
        $this->SoChungTu = $SoChungTu;
    }

 /**
     * @param field_type $LoaiChungTu
     */
    public function setLoaiChungTu($LoaiChungTu)
    {
        $this->LoaiChungTu = $LoaiChungTu;
    }

 /**
     * @param field_type $NgayChungTu
     */
    public function setNgayChungTu($NgayChungTu)
    {
        $this->NgayChungTu = \DateTime::createFromFormat("Y-m-d", $NgayChungTu);
    }



 /**
     * @param field_type $chitietchungtus
     */
    public function setChitietchungtus($chitietchungtus)
    {
        $this->chitietchungtus = $chitietchungtus;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

}