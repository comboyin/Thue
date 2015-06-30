<?php
namespace Quanlysothue\Froms;

use Zend\Form\Form;

class formDuKienTruyThu extends Form
{

    function __construct()
    {
        parent::__construct('');

        

        
        
        
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'KyThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
               
            ),
            'options' => array(
                'label' => 'Kỳ thuế'
            )
        ));

        $this->add(array(
            'name' => 'MaSoThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
               
                
            ),
            'options' => array(
                'label' => 'Mã số thuế'
            )
        ));
        
        

        
        $this->add(array(
            'name' => 'TieuMuc',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
               
            ),
            'options' => array(
                'label' => 'Tiểu mục'
            )
        ));
        
        $this->add(array(
            'name' => 'SoTien',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
               
            ),
            'options' => array(
                'label' => 'Số tiền'
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
            'name' => 'DoanhSo',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => ' '
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
            'name' => 'LyDo',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                 
            ),
            'options' => array(
                'label' => 'Lý do'
            )
        ));
   
        
    }
}

?>