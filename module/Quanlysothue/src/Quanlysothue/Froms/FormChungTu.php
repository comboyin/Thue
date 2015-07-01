<?php
namespace Quanlysothue\Froms;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Unlity\Unlity;

class FormChungTu extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        
        //SoChungTu
        $this->add(array(
            'name' => 'SoChungTu',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array()
        ));
       
        
       
        //MaSoThue
        $this->add(array(
            'name' => 'MaSoThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array()
        ));
        
        //NgayChungTu
        $this->add(array(
            'name' => 'NgayChungTu',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
            )
        ));
        
        
        //NgayHachToan
        $this->add(array(
            'name' => 'NgayHachToan',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
            )
        ));
        
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();
        
        
        
        //SoChungTu
        $inputFilter->add($factory->createInput([
            'name' => 'MaSoThue',
            'required' => false,
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
                        'min' => 9, // Minimum length
                        'max' => 20, // Maximum length, null if there is no length limitation
                        'encoding' => 'UTF-8'
                    )
                )
            )
        ]));
        
        
        
        
        //MaSoThue
        
        $inputFilter->add($factory->createInput([
            'name' => 'MaSoThue',
            'required' => false,
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
                        'min' => 9, // Minimum length
                        'max' => 20, // Maximum length, null if there is no length limitation
                        'encoding' => 'UTF-8'
                    )
                )
            )
        ]));
        
        
        //NgayChungTu
        $inputFilter->add($factory->createInput([
            'name' => 'NgayChungTu',
            'required' => false,
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
                    'name' => '\Zend\Validator\Date',
                    'options' => array(
                        'format' => 'd-m-Y'
                    )
                )
            )
        ]));
        
        
        //NgayHachToan
        $inputFilter->add($factory->createInput([
            'name' => 'NgayHachToan',
            'required' => false,
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
                    'name' => '\Zend\Validator\Date',
                    'options' => array(
                        'format' => 'd-m-Y'
                    )
                )
            )
        ]));
        
        $inputFilter->add($factory->createInput([
            'name' => 'TenHKD',
            'required' => false,
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
                        'max' => '128'
                    )
                )
            )
        ]));
        
        $inputFilter->add($factory->createInput([
            'name' => 'SoCMND',
            'required' => false,
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
                        'min' => '9',
                        'max' => '9'
                    )
                )
            )
        ]));
        
        $inputFilter->add($factory->createInput([
            'name' => 'DiaChiCT',
            'required' => false,
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
                        'max' => '255'
                    )
                )
            )
        ]));
        
        $inputFilter->add($factory->createInput([
            'name' => 'DiaChiKD',
            'required' => false,
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
                        'max' => '255'
                    )
                )
            )
        ]));
        // selected Phuong
        // selected Quan
        
        $inputFilter->add($factory->createInput([
            'name' => 'ChanLe',
            'required' => false,
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
                        'max' => '4'
                    )
                )
            )
        ]));
        
        $inputFilter->add($factory->createInput([
            'name' => 'Hem',
            'required' => false,
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
            'name' => 'SoNha',
            'required' => false,
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
            'name' => 'SoNhaPhu',
            'required' => false,
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
                        'max' => '10'
                    )
                )
            )
        ]));
        
        $inputFilter->add($factory->createInput([
            'name' => 'TenDuong',
            'required' => false,
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
                        'max' => '127'
                    )
                )
            )
        ]));
        
        $inputFilter->add($factory->createInput([
            'name' => 'SoGPKD',
            'required' => false,
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
                        'min' => '9',
                        'max' => '21'
                    )
                )
            )
        ]));
        
        $inputFilter->add($factory->createInput([
            'name' => 'ThoiDiemBDKD',
            'required' => false,
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
                    'name' => '\Zend\Validator\Date',
                    'options' => array(
                        'format' => 'd-m-Y'
                    )
                )
            )
        ]));
        
        // option Nganh
        
        $inputFilter->add($factory->createInput([
            'name' => 'Nghe',
            'required' => false,
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
                        'max' => '255'
                    )
                )
            )
        ]));
        
        
        
        $this->setInputFilter($inputFilter);
    }
}