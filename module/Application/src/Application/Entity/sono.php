<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="sono",
 *     indexes={
 *         @ORM\Index(name="fk_sono_muclucngansach1_idx", columns={"TieuMuc"}),
 *         @ORM\Index(name="fk_sono_nguoinopthue1_idx", columns={"MaSoThue"})
 *     }
 * )
 */
class sono
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=7)
     */
    private $KyLapBo;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=7)
     */
    private $KyThue;



    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":"0"})
     */
    private $SoTien;



    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\buttoannothue", mappedBy="sono")
     */
    private $buttoannothues;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="sonos")
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="sonos")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
 /**
     * @return the $KyLapBo
     */
    public function getKyLapBo()
    {
        return $this->KyLapBo;
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
     * @return the $buttoannothues
     */
    public function getButtoannothues()
    {
        return $this->buttoannothues;
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
     * @param field_type $KyLapBo
     */
    public function setKyLapBo($KyLapBo)
    {
        $this->KyLapBo = $KyLapBo;
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
     * @param field_type $buttoannothues
     */
    public function setButtoannothues($buttoannothues)
    {
        $this->buttoannothues = $buttoannothues;
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