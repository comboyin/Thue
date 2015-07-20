<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="thue",
 * indexes={
 * @ORM\Index(name="fk_thue_muclucngansach1_idx", columns={"TieuMuc"}),
 * @ORM\Index(name="fk_thue_nguoinopthue1_idx", columns={"MaSoThue"})
 * })
 */
class thue
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=7)
     */
    private $KyThue;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="thues")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="thues")
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $TenGoi;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $SanLuong;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $GiaTinhThue;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $DoanhThuChiuThue;
    


    /**
     * @ORM\Column(type="float",nullable=false, options={"default":"1"})
     */
    private $TiLeTinhThue;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default":"1"})
     */
    private $ThueSuat;

    /**
     * @ORM\Column(type="integer",nullable=false,options={"default":"0"})
     */
    private $SoTien;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $NgayPhaiNop;


    /**
     * @ORM\Column(type="integer", length=1, nullable=false,options={"default":"0"})
     */
    private $TrangThai;
    
    /**
     * @return the $TrangThai
     */
    public function getTrangThai()
    {
        return $this->TrangThai;
    }

 /**
     * @param field_type $TrangThai
     */
    public function setTrangThai($TrangThai)
    {
        $this->TrangThai = $TrangThai;
    }

 /**
     * @return the $KyThue
     */
    public function getKyThue()
    {
        return $this->KyThue;
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @return the $muclucngansach
     */
    public function getMuclucngansach()
    {
        return $this->muclucngansach;
    }

 /**
     * @return the $TenGoi
     */
    public function getTenGoi()
    {
        return $this->TenGoi;
    }

 /**
     * @return the $SanLuong
     */
    public function getSanLuong()
    {
        return $this->SanLuong;
    }

 /**
     * @return the $GiaTinhThue
     */
    public function getGiaTinhThue()
    {
        return $this->GiaTinhThue;
    }

 /**
     * @return the $DoanhThuChiuThue
     */
    public function getDoanhThuChiuThue()
    {
        return $this->DoanhThuChiuThue;
    }



 /**
     * @return the $TiLeTinhThue
     */
    public function getTiLeTinhThue()
    {
        return $this->TiLeTinhThue;
    }

 /**
     * @return the $ThueSuat
     */
    public function getThueSuat()
    {
        return $this->ThueSuat;
    }

 /**
     * @return the $SoTien
     */
    public function getSoTien()
    {
        return $this->SoTien;
    }

 /**
     * @return the $NgayPhaiNop
     */
    public function getNgayPhaiNop()
    {
        return $this->NgayPhaiNop->format('d-m-Y');
    }

 /**
     * @return the $KetSo
     */
    public function getKetSo()
    {
        return $this->KetSo;
    }

 /**
     * @param field_type $KyThue
     */
    public function setKyThue($KyThue)
    {
        $this->KyThue = $KyThue;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

 /**
     * @param field_type $muclucngansach
     */
    public function setMuclucngansach($muclucngansach)
    {
        $this->muclucngansach = $muclucngansach;
    }

 /**
     * @param field_type $TenGoi
     */
    public function setTenGoi($TenGoi)
    {
        $this->TenGoi = $TenGoi;
    }

 /**
     * @param field_type $SanLuong
     */
    public function setSanLuong($SanLuong)
    {
        $this->SanLuong = $SanLuong;
    }

 /**
     * @param field_type $GiaTinhThue
     */
    public function setGiaTinhThue($GiaTinhThue)
    {
        $this->GiaTinhThue = $GiaTinhThue;
    }

 /**
     * @param field_type $DoanhThuChiuThue
     */
    public function setDoanhThuChiuThue($DoanhThuChiuThue)
    {
        $this->DoanhThuChiuThue = $DoanhThuChiuThue;
    }



 /**
     * @param field_type $TiLeTinhThue
     */
    public function setTiLeTinhThue($TiLeTinhThue)
    {
        $this->TiLeTinhThue = $TiLeTinhThue;
    }

 /**
     * @param field_type $ThueSuat
     */
    public function setThueSuat($ThueSuat)
    {
        $this->ThueSuat = $ThueSuat;
    }

 /**
     * @param field_type $SoTien
     */
    public function setSoTien($SoTien)
    {
        $this->SoTien = $SoTien;
    }

 /**
     * @param field_type $NgayPhaiNop
     */
    public function setNgayPhaiNop($NgayPhaiNop)
    {
        $this->NgayPhaiNop = $NgayPhaiNop;
    }

 /**
     * @param field_type $KetSo
     */
    public function setKetSo($KetSo)
    {
        $this->KetSo = $KetSo;
    }




}
