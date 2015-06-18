<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\Callback;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="dukientruythu",
 * indexes={
 * @ORM\Index(name="fk_dukientruythu_nguoinopthue1_idx", columns={"MaSoThue"}),
 * @ORM\Index(name="fk_dukientruythu_muclucngansach1_idx", columns={"TieuMuc"})
 * }
 * )
 */
class dukientruythu implements InputFilterAwareInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=10,nullable=false)
     */
    private $KyThue;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $DoanhSo;

    /**
     * @ORM\Column(type="float",nullable=false, options={"default":"1"})
     */
    private $TiLeTinhThue;

    /**
     * @ORM\Column(type="integer",nullable=false, options={"default":"0"})
     */
    private $SoTien;

    /**
     * @ORM\Column(type="integer",length=1,nullable=false,options={"default":"0"})
     */
    private $TrangThai;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $LyDo;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\muclucngansach", inversedBy="dukientruythus")
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict")
     */
    private $muclucngansach;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="dukientruythus")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;

    protected $inputFilter;

    /**
     *
     * @return the $KyThue
     */
    public function getKyThue()
    {
        return $this->KyThue;
    }

    /**
     *
     * @return the $DoanhSo
     */
    public function getDoanhSo()
    {
        return $this->DoanhSo;
    }

    /**
     *
     * @return the $TiLeTinhThue
     */
    public function getTiLeTinhThue()
    {
        return $this->TiLeTinhThue;
    }

    /**
     *
     * @return the $SoTien
     */
    public function getSoTien()
    {
        return $this->SoTien;
    }

    /**
     *
     * @return the $TrangThai
     */
    public function getTrangThai()
    {
        return $this->TrangThai;
    }

    /**
     *
     * @return the $LyDo
     */
    public function getLyDo()
    {
        return $this->LyDo;
    }

    /**
     *
     * @return the $muclucngansach
     */
    public function getMuclucngansach()
    {
        return $this->muclucngansach;
    }

    /**
     *
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

    /**
     *
     * @param field_type $KyThue            
     */
    public function setKyThue($KyThue)
    {
        $this->KyThue = $KyThue;
    }

    /**
     *
     * @param field_type $DoanhSo            
     */
    public function setDoanhSo($DoanhSo)
    {
        $this->DoanhSo = $DoanhSo;
    }

    /**
     *
     * @param field_type $TiLeTinhThue            
     */
    public function setTiLeTinhThue($TiLeTinhThue)
    {
        $this->TiLeTinhThue = $TiLeTinhThue;
    }

    /**
     *
     * @param field_type $SoTien            
     */
    public function setSoTien($SoTien)
    {
        $this->SoTien = $SoTien;
    }

    /**
     *
     * @param field_type $TrangThai            
     */
    public function setTrangThai($TrangThai)
    {
        $this->TrangThai = $TrangThai;
    }

    /**
     *
     * @param field_type $LyDo            
     */
    public function setLyDo($LyDo)
    {
        $this->LyDo = $LyDo;
    }

    /**
     *
     * @param field_type $muclucngansach            
     */
    public function setMuclucngansach($muclucngansach)
    {
        $this->muclucngansach = $muclucngansach;
    }

    /**
     *
     * @param field_type $nguoinopthue            
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
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
                            'format' => 'm/Y'
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
                            'min' => '8',
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
                'name' => 'TrangThai',
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
                'name' => 'LyDo',
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
                            'min' => '0',
                            'max' => '255'
                        )
                    )
                )
            ]));
            
            
            
            
            
            $callback = new Callback([$this, 'validateFloat']);
            $callback->setMessage('The value is not valid.');
            
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
                'validators' =>array(
                    array(
                        'name' => '\Zend\Validator\Step',
                        'options' => array(
                            'baseValue' => 0.0,
                            'step'      => 0.1,
                        )
                    ),
                    array(
                        'name' => '\Zend\I18n\Validator\IsFloat',
                        'options' => array(
                            'min' => 0,
                            'locale'=>'en'
                        )
                    )
                   
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'DoanhSo',
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
                    ),
                    
                    array(
                        'name' => 'GreaterThan',
                        'options' => array(
                            'min' => 0
                        )
                    )
                )
            ]));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
    
    public function validateFloat($value){
        return (is_numeric($value));
    }
}