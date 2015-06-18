<?php
namespace Quanlysothue\Froms;

use Zend\Form\Form;

class FormDKThue extends Form
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
                /* 'readonly' => ' ', */
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
            'name' => 'TieuMuc',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Doanh số'
            )
        ));
        
        $this->add(array(
            'name' => 'TenGoi',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Tên gọi'
            )
        ));

        
        $this->add(array(
            'name' => 'TieuMuc',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Tiểu mục'
            )
        ));
        
        $this->add(array(
            'name' => 'SanLuong',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'Sản lượng'
            )
        ));
        
        $this->add(array(
            'name' => 'DoanhThuChiuThue',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'Doanh thi chịu thuế'
            )
        ));
        

        $this->add(array(
            'name' => 'ThueSuat',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'Thuế Suất'
            )
        ));
        
        $this->add(array(
            'name' => 'TiLeTinhThue',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'Tỉ lệ tính thuế'
            )
        ));
        
        $this->add(array(
            'name' => 'SoTien',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                'required' => 'required',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'Số Tiền'
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
        
    }
}

?>