<?php
namespace Quanlynguoinopthue\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Unlity\Unlity;

class formThongTinNgungNghi extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        
        //LyDo
        $this->add(array(
            'name' => 'LyDo',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array()
        ));
       
        
       
        //MaSoThue
        $this->add(array(
            'name' => 'MaSoThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array()
        ));
        
        //TuNgay
        $this->add(array(
            'name' => 'TuNgay',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
            )
        ));
        //DenNgay
        $this->add(array(
            'name' => 'DenNgay',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
            )
        ));
        
        //NgayNopDon
        $this->add(array(
            'name' => 'NgayNopDon',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
            )
        ));
        
        
        //MaTTNgungNghi
        $this->add(array(
            'name' => 'MaTTNgungNghi',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
            )
        ));
        
        

        
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();
        
        
        
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
        
        
        
        //LyDo
        $inputFilter->add($factory->createInput([
            'name' => 'LyDo',
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
                        'min' => 10, // Minimum length
                        'max' => 255, // Maximum length, null if there is no length limitation
                        'encoding' => 'UTF-8'
                    )
                )
            )
        ]));
        
        
        
        //MaTTNgungNghi
        $inputFilter->add($factory->createInput([
            'name' => 'MaTTNgungNghi',
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
                        'min' => 1, // Minimum length
                        'max' => 10, // Maximum length, null if there is no length limitation
                        'encoding' => 'UTF-8'
                    )
                )
            )
        ]));
        
        
        
        
       
        
        
        //NgayChungTu
        $inputFilter->add($factory->createInput([
            'name' => 'TuNgay',
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
                    'name' => '\Zend\Validator\Date',
                    'options' => array(
                        'format' => 'd-m-Y'
                    )
                )
            )
        ]));
        
        
        //DenNgay
        $inputFilter->add($factory->createInput([
            'name' => 'DenNgay',
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
                    'name' => '\Zend\Validator\Date',
                    'options' => array(
                        'format' => 'd-m-Y'
                    )
                )
            )
        ]));
        
        //NgayNopDon
        $inputFilter->add($factory->createInput([
            'name' => 'NgayNopDon',
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
                    'name' => '\Zend\Validator\Date',
                    'options' => array(
                        'format' => 'd-m-Y'
                    )
                )
            )
        ]));
        
        
        
        
        
        

        
        $this->setInputFilter($inputFilter);
    }
}