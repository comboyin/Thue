<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="dukienthue",
 * indexes={
 * @ORM\Index(name="fk_dukienthue_nguoinopthue1_idx", columns={"MaSoThue"}),
 * @ORM\Index(name="fk_dukienthue_muclucngansach1_idx", columns={"TieuMuc"}),
 * @ORM\Index(name="fk_dukienthue_user1_idx", columns={"MaUser"})
 * })
 */
class dukienthue implements InputFilterAwareInterface
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="dukienthues")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="dukienthues")
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;

    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=7,nullable=false)
     */
    private $KyThue;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $TenGoi;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $SanLuong;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $DoanhThuChiuThue;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $GiaTinhThue;

    /**
     * @ORM\Column(type="float",nullable=false,options={"default":"0"})
     */
    private $TiLeTinhThue;

    /**
     * @ORM\Column(type="integer",nullable=false,options={"default":"1"})
     */
    private $ThueSuat;

    /**
     * @ORM\Column(type="integer",nullable=false,options={"default":"0"})
     */
    private $SoTien;
    
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $NgayPhaiNop;

    /**
     * @ORM\Column(type="integer",length=1,nullable=false,options={"default":"0"})
     */
    private $TrangThai;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\user", inversedBy="dukienthues")
     * @ORM\JoinColumn(name="MaUser", referencedColumnName="MaUser", nullable=false, onDelete="restrict")
     */
    private $user;
    
    protected $inputFilter;


    /**
     * @return user
     */
    public function getUser()
    {
        return $this->user;
    }

 /**
     * @param field_type $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return the $KyThue
     */
    public function getKyThue()
    {
        return $this->KyThue;
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
     * @return the $DoanhThuChiuThue
     */
    public function getDoanhThuChiuThue()
    {
        return $this->DoanhThuChiuThue;
    }

 /**
     * @return the $GiaTinhThue
     */
    public function getGiaTinhThue()
    {
        return $this->GiaTinhThue;
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
        return $this->NgayPhaiNop;
    }

 /**
     * @return the $TrangThai
     */
    public function getTrangThai()
    {
        return $this->TrangThai;
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
     * @param field_type $KyThue
     */
    public function setKyThue($KyThue)
    {
        $this->KyThue = $KyThue;
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
     * @param field_type $DoanhThuChiuThue
     */
    public function setDoanhThuChiuThue($DoanhThuChiuThue)
    {
        $this->DoanhThuChiuThue = $DoanhThuChiuThue;
    }

 /**
     * @param field_type $GiaTinhThue
     */
    public function setGiaTinhThue($GiaTinhThue)
    {
        $this->GiaTinhThue = $GiaTinhThue;
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
        
        $this->NgayPhaiNop =  \DateTime::createFromFormat('Y-m-d', $NgayPhaiNop);
    }

 /**
     * @param field_type $TrangThai
     */
    public function setTrangThai($TrangThai)
    {
        $this->TrangThai = $TrangThai;
    }

 public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            $inputFilter->add($factory->createInput([
                'name' => 'KyThue',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    
                    array(
                        'name' => '\Zend\Validator\StringLength',
                        'options' => array(
                            'min' => 7, // Minimum length
                            'max' => 7, // Maximum length, null if there is no length limitation
                            'encoding' => 'UTF-8'
                        )
                    ), // Encoding to use
                    
                    array(
                        'name' => '\Zend\Validator\Date',
                        'options' => array(
                            'format' => 'm-Y'
                        )
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'MaSoThue',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => '10',
                            'max' => '20'
                        )
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'TieuMuc',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => '4',
                            'max' => '4'
                        )
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'SanLuong',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\Digits',
                        'options' => array()
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'ThueSuat',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\Digits',
                        'options' => array()
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'DoanhThuChiuThue',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\Digits',
                        'options' => array()
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'TiLeTinhThue',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\Digits',
                        'options' => array()
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'SoTien',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\Digits',
                        'options' => array()
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'HanhDong',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => '2',
                            'max' => '100'
                        )
                    )
                )
            ]));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
}