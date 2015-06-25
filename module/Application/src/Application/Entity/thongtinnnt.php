<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="thongtinnnt",
 *     indexes={
 *         @ORM\Index(name="fk_diachikd_phuong1_idx", columns={"MaPhuong"}),
 *         @ORM\Index(name="fk_diachikd_nguoinopthue1_idx", columns={"MaSoThue"})
 *     }
 * )
 */
class thongtinnnt
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $IDthongtinnnt;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $DiaChiKD;
    
    
    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $ChanLe;
    
    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $Hem;
    
    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    private $SoNha;
    
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $SoNhaPhu;
    
    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $TenDuong;
    
    
    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiGianBatDau;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ThoiGianKetThuc;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\phuong", inversedBy="thongtinnnts")
     * @ORM\JoinColumn(name="MaPhuong", referencedColumnName="MaPhuong", nullable=false, onDelete="restrict")
     */
    private $phuong;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="thongtinnnt")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
 /**
     * @return the $IDthongtinnnt
     */
    public function getIDthongtinnnt()
    {
        return $this->IDthongtinnnt;
    }

 /**
     * @return the $DiaChiKD
     */
    public function getDiaChiKD()
    {
        return $this->DiaChiKD;
    }

 /**
     * @return the $ChanLe
     */
    public function getChanLe()
    {
        return $this->ChanLe;
    }

 /**
     * @return the $Hem
     */
    public function getHem()
    {
        return $this->Hem;
    }

 /**
     * @return the $SoNha
     */
    public function getSoNha()
    {
        return $this->SoNha;
    }

 /**
     * @return the $SoNhaPhu
     */
    public function getSoNhaPhu()
    {
        return $this->SoNhaPhu;
    }

 /**
     * @return the $TenDuong
     */
    public function getTenDuong()
    {
        return $this->TenDuong;
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
     * @return phuong
     */
    public function getPhuong()
    {
        return $this->phuong;
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @param field_type $IDthongtinnnt
     */
    public function setIDthongtinnnt($IDthongtinnnt)
    {
        $this->IDthongtinnnt = $IDthongtinnnt;
    }

 /**
     * @param field_type $DiaChiKD
     */
    public function setDiaChiKD($DiaChiKD)
    {
        $this->DiaChiKD = $DiaChiKD;
    }

 /**
     * @param field_type $ChanLe
     */
    public function setChanLe($ChanLe)
    {
        $this->ChanLe = $ChanLe;
    }

 /**
     * @param field_type $Hem
     */
    public function setHem($Hem)
    {
        $this->Hem = $Hem;
    }

 /**
     * @param field_type $SoNha
     */
    public function setSoNha($SoNha)
    {
        $this->SoNha = $SoNha;
    }

 /**
     * @param field_type $SoNhaPhu
     */
    public function setSoNhaPhu($SoNhaPhu)
    {
        $this->SoNhaPhu = $SoNhaPhu;
    }

 /**
     * @param field_type $TenDuong
     */
    public function setTenDuong($TenDuong)
    {
        $this->TenDuong = $TenDuong;
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
     * @param field_type $phuong
     */
    public function setPhuong($phuong)
    {
        $this->phuong = $phuong;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }


}