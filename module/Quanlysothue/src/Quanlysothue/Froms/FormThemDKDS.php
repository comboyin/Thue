<?php
namespace Quanlysothue\Froms;

use Zend\Form\Form;

class FormThemDKDS extends Form
{

    function __construct()
    {
        parent::__construct('');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'KyThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'm-ctrl-medium',
                'size' => '16',
                'readonly' => ' ',
                'id'=>'kythue'
            ),
            'options' => array(
                'label' => 'Kỳ thuế'
            )
        ));

        $this->add(array(
            'name' => 'MaSoThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'class' => 'm-ctrl-medium',
                'style' => "margin: 0 auto;",
                'data-provide' => 'typeahead',
                'data-items' => '10',
                'id'=>'MaSoThue',
                'autocomplete'=>"off"
            ),
            'options' => array(
                'label' => 'Mã số thuế'
            )
        ));

        $this->add(array(
            'name' => 'DoanhSo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'min' => '1000000',
                'max' => '9000000000',
                'step' => '1000000',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'Doanh số'
            )
        ));

        $this->add(array(
            'name' => 'TrangThai',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'options' => array(
                'label' => 'Trang thái'
            )
        ));

        $this->add(array(
            'name' => 'HanhDong',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
        $this->add(array(
            'name' => 'MaUser',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => ' '
            )
        ));
        
    }
}

?>