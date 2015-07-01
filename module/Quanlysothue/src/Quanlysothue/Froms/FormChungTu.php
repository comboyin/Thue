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
        
        
        //NgayHachToan
        $inputFilter->add($factory->createInput([
            'name' => 'NgayHachToan',
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