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

class FormCTMienGiam extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        
        //KyThue
         $this->add(array(
            'name' => 'KyThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
               
            ),
            'options' => array(
                'label' => 'Kỳ thuế'
            )
        ));

        
       
        //TieuMuc
        $this->add(array(
            'name' => 'TieuMuc',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
               
            ),
            'options' => array(
                'label' => 'Tiểu mục'
            )
        ));
        
        //SoTien
        $this->add(array(
            'name' => 'SoTien',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
               
            ),
            'options' => array(
                'label' => 'Số tiền'
            )
        ));
        
        
       
        
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();
        
        
        
        //KyThue
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
        
        
        
        
        //TieuMuc
        
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
        
        

        
        //SoTien
         $inputFilter->add($factory->createInput([
                'name' => 'SoTien',
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
        
        
        
        $this->setInputFilter($inputFilter);
    }
}