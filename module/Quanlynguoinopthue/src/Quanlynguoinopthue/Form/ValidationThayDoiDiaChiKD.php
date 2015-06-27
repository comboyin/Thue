<?php 
namespace Quanlynguoinopthue\Form;






use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ValidationThayDoiDiaChiKD implements InputFilterAwareInterface{

    private $inputFilter;

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
            ]
            ));
            
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
            ]
            ));
            
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
                'name' => 'Phuong',
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
                            'min' => '8',
                            'max' => '20'
                        )
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'Quan',
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
                            'min' => '5',
                            'max' => '20'
                        )
                    )
                )
            ]));
            
            
            $inputFilter->add($factory->createInput([
                'name' => 'ThoiDiemThayDoi',
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
                        'name' => '\Zend\Validator\date',
                        'options' => array(
                            'format' => 'd-m-Y'
                        )
                    )
                )
            ]));
            
           
          
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
    
    
}



