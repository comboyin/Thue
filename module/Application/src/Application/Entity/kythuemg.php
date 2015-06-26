<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="kythuemg", indexes={@ORM\Index(name="fk_kythuemg_miengiamthue1_idx", columns={"SoQDMG"})})
 */
class kythuemg
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=7)
     */
    private $KyThue;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":"0"})
     */
    private $SoTienMG;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="kythuemgs")
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\miengiamthue", inversedBy="kythuemgs")
     * @ORM\JoinColumn(name="SoQDMG", referencedColumnName="SoQDMG", nullable=false, onDelete="restrict")
     */
    private $miengiamthue;
 /**
     * @return the $KyThue
     */
    public function getKyThue()
    {
        return $this->KyThue;
    }

 /**
     * @return the $SoTienMG
     */
    public function getSoTienMG()
    {
        return $this->SoTienMG;
    }

 /**
     * @return the $muclucngansach
     */
    public function getMuclucngansach()
    {
        return $this->muclucngansach;
    }

 /**
     * @return the $miengiamthue
     */
    public function getMiengiamthue()
    {
        return $this->miengiamthue;
    }

 /**
     * @param field_type $KyThue
     */
    public function setKyThue($KyThue)
    {
        $this->KyThue = $KyThue;
    }

 /**
     * @param field_type $SoTienMG
     */
    public function setSoTienMG($SoTienMG)
    {
        $this->SoTienMG = $SoTienMG;
    }

 /**
     * @param field_type $muclucngansach
     */
    public function setMuclucngansach($muclucngansach)
    {
        $this->muclucngansach = $muclucngansach;
    }

 /**
     * @param field_type $miengiamthue
     */
    public function setMiengiamthue($miengiamthue)
    {
        $this->miengiamthue = $miengiamthue;
    }

}