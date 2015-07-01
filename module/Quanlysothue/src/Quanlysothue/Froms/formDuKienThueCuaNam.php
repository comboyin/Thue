<?php
namespace Quanlysothue\Froms;


use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
//use Zend\InputFilter\InputFilterAwareInterface;
//use Zend\InputFilter\InputFilterInterface;

class formDuKienThueCuaNam extends Form
{

    function __construct()
    {
        parent::__construct('');
       

        parent::__construct(''); 
        $this->addElements();
        $this->addInputFilter();
    }
    
    public function addElements()
    {
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'KyThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'MaSoThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                 
        
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'TieuMuc',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'DoanhThuChiuThue',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'TiLeTinhThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'ThueSuat',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'TenGoi',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'SanLuong',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'GiaTinhThue',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'SoTien',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
            )
        ));
    }
    
    public function addInputFilter()
    {
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
                        'min' => 4, // Minimum length
                        'max' => 4, // Maximum length, null if there is no length limitation
                        'encoding' => 'UTF-8'
                    )
                ), // Encoding to use
        
                array(
                    'name' => '\Zend\Validator\Date',
                    'options' => array(
                        'format' => 'Y'
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
                ),
        
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => 0
                    )
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
                    'name' => '\Zend\Validator\Step',
                    'options' => array(
                        'baseValue' => 0.000,
                        'step' => 0.001
                    )
                ),
                array(
                    'name' => '\Zend\I18n\Validator\IsFloat',
                    'options' => array(
                        'min' => 0,
                        'locale' => 'en'
                    )
                )
            )
        ]
        ));
        
        $inputFilter->add($factory->createInput([
            'name' => 'ThueSuat',
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
            'name' => 'TenGoi',
            'required' => false, //true quan trong k dc bo trong
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
        
        $inputFilter->add($factory->createInput([
            'name' => 'SanLuong',
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
            'name' => 'GiaTinhThue',
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
                ),
        
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => 0
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
                ),
        
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => 0
                    )
                )
            )
        ]));
        
        $this->setInputFilter($inputFilter);
    }
}

?>
