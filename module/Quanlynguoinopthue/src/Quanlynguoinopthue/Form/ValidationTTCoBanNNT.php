<?php 
namespace Quanlynguoinopthue\Form;






use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ValidationTTCoBanNNT implements InputFilterAwareInterface{

    private $inputFilter;

    public function __construct(){
        
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
                'name' => 'NgayCapMST',
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
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }

    
    
}



