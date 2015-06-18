<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="nntnganh",
 *     indexes={
 *         @ORM\Index(name="fk_nguoinopthue_has_nganh_nganh1_idx", columns={"MaNganh"}),
 *         @ORM\Index(name="fk_nguoinopthue_has_nganh_nguoinopthue1_idx", columns={"MaSoThue"})
 *     }
 * )
 */
class NNTNganh
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $IDNNTNganh;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiGianBatDau;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiGianKetThuc;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="NNTNganhs")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nganh", inversedBy="NNTNganhs")
     * @ORM\JoinColumn(name="MaNganh", referencedColumnName="MaNganh", nullable=false, onDelete="restrict")
     */
    private $nganh;
 /**
     * @return the $IDNNTNganh
     */
    public function getIDNNTNganh()
    {
        return $this->IDNNTNganh;
    }

 /**
     * @return the $ThoiGianBatDau
     */
    public function getThoiGianBatDau()
    {
        return $this->ThoiGianBatDau;
    }

 /**
     * @return the $ThoiGianKetThuc
     */
    public function getThoiGianKetThuc()
    {
        return $this->ThoiGianKetThuc;
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @return nganh
     */
    public function getNganh()
    {
        return $this->nganh;
    }

 /**
     * @param field_type $IDNNTNganh
     */
    public function setIDNNTNganh($IDNNTNganh)
    {
        $this->IDNNTNganh = $IDNNTNganh;
    }

 /**
     * @param field_type $ThoiGianBatDau
     */
    public function setThoiGianBatDau($ThoiGianBatDau)
    {
        $this->ThoiGianBatDau = $ThoiGianBatDau;
    }

 /**
     * @param field_type $ThoiGianKetThuc
     */
    public function setThoiGianKetThuc($ThoiGianKetThuc)
    {
        $this->ThoiGianKetThuc = $ThoiGianKetThuc;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

 /**
     * @param field_type $nganh
     */
    public function setNganh($nganh)
    {
        $this->nganh = $nganh;
    }

}