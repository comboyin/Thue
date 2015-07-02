<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="chitietchungtu",
 *     indexes={
 *         @ORM\Index(name="fk_chitietchungtu_muclucngansach1_idx", columns={"TieuMuc"}),
 *         @ORM\Index(name="fk_chitietchungtu_chungtu1_idx", columns={"SoChungTu"})
 *     }
 * )
 */
class chitietchungtu
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=7)
     */
    private $KyThue;
    
    
    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $NgayHachToan;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":"0"})
     */
    private $SoTien;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="chitietchungtus")
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\chungtu", inversedBy="chitietchungtus")
     * @ORM\JoinColumn(name="SoChungTu", referencedColumnName="SoChungTu", nullable=false, onDelete="restrict")
     */
    private $chungtu;
 /**
     * @return the $NgayHachToan
     */
    public function getNgayHachToan()
    {
        return $this->NgayHachToan;
    }

 /**
     * @param field_type $NgayHachToan
     */
    public function setNgayHachToan($NgayHachToan)
    {
        $this->NgayHachToan = \DateTime::createFromFormat("Y-m-d", $NgayHachToan);
    }

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
     * @return the $muclucngansach
     */
    public function getMuclucngansach()
    {
        return $this->muclucngansach;
    }

 /**
     * @return the $chungtu
     */
    public function getChungtu()
    {
        return $this->chungtu;
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
     * @param field_type $muclucngansach
     */
    public function setMuclucngansach($muclucngansach)
    {
        $this->muclucngansach = $muclucngansach;
    }

 /**
     * @param field_type $chungtu
     */
    public function setChungtu($chungtu)
    {
        $this->chungtu = $chungtu;
    }

}