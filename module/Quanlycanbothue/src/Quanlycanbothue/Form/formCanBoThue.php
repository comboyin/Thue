<?php
namespace Quanlycanbothue\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class formCanBoThue extends Form
{
    function __construct()
    {
        parent::__construct('');
        $this->addElements();
        $this->addInputFilter();
    }
    
    public function addElements()
    {
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'MaUser',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span4",
                "id" => "MaUser"
            )
        ));
        
        $this->add(array(
            'name' => 'TenUser',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span4",
                "id" => "TenUser"
            )
        ));
        
        $this->add(array(
            'name' => 'Email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                "required" => "required",
                'class' => "span6",
                "id" => "Email"
            )
        ));
        
        $this->add(array(
            'name' => 'MatKhau',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                "required" => "required",
                'class' => "span6",
                "id" => "MatKhau"
            )
        ));

    }
    
    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();
        
        $inputFilter->add($factory->createInput([
            'name' => 'MaUser',
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
        ]
        ));
        
        $inputFilter->add($factory->createInput([
            'name' => 'TenUser',
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
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => '4'
                    )
                )
            )
        ]));
        
        //email
        $email = new Input('email');
        $email->getValidatorChain()->attach(new Validator\EmailAddress());
        $inputFilter->add($email);
        
        
        $inputFilter->add($factory->createInput([
            'name' => 'MatKhau',
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
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => '5'
                    )
                )
            )
        ]));
        
        $this->setInputFilter($inputFilter);
    }
}