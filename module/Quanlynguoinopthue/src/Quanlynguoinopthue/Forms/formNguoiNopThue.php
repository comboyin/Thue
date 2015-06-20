<?php
namespace Quanlynguoinopthue\Forms;

use Zend\Form\Form;

class formNguoiNopThue extends Form
{

    function __construct()
    {
        parent::__construct('formNguoiNopThue');
        
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'MaSoThue',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span6",
                'maxlength'=>"15"
            )
            
        ));
        
        $this->add(array(
            'name' => 'NgayCapMST',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'id' => "dp_NgayCapMST",
                'value' => "",
                'size' => "16",
                'class' => "m-ctrl-medium popovers",
                'data-trigger' => "hover",
                'data-content' => "Ngày cấp Mã Số Thuế",
                'data-original-title' => "Ngày cấp MST"
            )
        ));
        
        $this->add(array(
            'name' => 'TenHKD',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span6"
            )
        ));
        
        $this->add(array(
            'name' => 'SoCMND',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                "class" => "span6  popovers",
                "data-trigger" => "hover",
                "data-content" => "Nhập số chứng minh nhân dân của hộ kinh doanh",
                "data-original-title" => "Số CMND"
            )
        ));
        
        $this->add(array(
            'name' => 'DiaChiCT',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span6"
            )
        ));
        
        $this->add(array(
            'name' => 'DiaChiKD',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span2",
                'placeholder' => "Số nhà, Tên đường"
            )
        ));
        /*
         *
         * $select->setValueOptions(array(
         * 'foo' => array (
         * 'value' => 'Foo Option',
         * 'label' => 'Foo label',
         * 'selected' => TRUE,
         * 'disabled' => FALSE
         * ),
         * 'bar' => array (
         * 'value' => 'Bar Option',
         * 'label' => 'Bar label',
         * ),
         * );
         *
         */
        $this->add(array(
            'name' => 'Phuong',
            'type' => '\Zend\Form\Element\Select',
            'attributes' => array(
                "required" => "required",
                'class' => "span2",
                'placeholder' => "Chọn phường..."
            ),
            'options' => array(
                'label' => 'Chọn phường...',
                'value_options' => array(
                    'disabled' => array(
                        'value' => ' ',
                        'label' => 'Chọn phường...',
                        'selected' => "selected",
                        'disabled' => TRUE
                    )
                )
                
            )
            
        ));
        
        $this->add(array(
            'name' => 'DiaChiKD',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span2",
                'placeholder' => "Số nhà, Tên đường"
            )
        ));
        
        $this->add(array(
            'name' => 'Quan',
            'type' => '\Zend\Form\Element\Select',
            'attributes' => array(
                "required" => "required",
                'class' => "span2",
                'placeholder' => "Chọn quận..."
            ),
            'options' => array(
                'label' => 'Chọn quận...',
                'value_options' => array(
                    'disabled' => array(
                        'value' => ' ',
                        'label' => 'Chọn quận...',
                        'selected' => TRUE,
                        'disabled' => TRUE
                    )
                )
            )
            
        ));
        
        $this->add(array(
            'name' => 'SoGPKD',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span6  popovers",
                'data-trigger' => "hover",
                'data-content' => "Nhập Số Giấy Phép Đăng Ký Kinh Doanh",
                'data-original-title' => "Số GPKD"
            )
        ));
        
        $this->add(array(
            'name' => 'ChanLe',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                
                'style' => "width: 60px",
                'placeholder' => "Chẳn Lẻ"
            )
        ));
        
        $this->add(array(
            'name' => 'Hem',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                
                'class' => "span1",
                'placeholder' => "Hẻm"
            )
        ));
        
        $this->add(array(
            'name' => 'SoNha',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
                
                'style' => "width: 60px",
                'placeholder' => "Số Nhà"
            )
        ));
        
        $this->add(array(
            'name' => 'SoNhaPhu',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                
                'style' => "width: 80px",
                'placeholder' => "Số Nhà Phụ"
            )
        ));
        
        $this->add(array(
            'name' => 'TenDuong',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                
                'class' => "span2",
                'placeholder' => "Tên Đường"
            )
        ));
        
        $this->add(array(
            'name' => 'ThoiDiemBDKD',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'id' => "dp_ThoiDiemBDKD",
                'type' => "text",
                'value' => "",
                'size' => "16",
                'class' => "m-ctrl-medium popovers",
                'data-trigger' => "hover",
                'data-content' => "Thời điểm bắt đầu kinh doanh",
                'data-original-title' => "Thời điểm BDKD"
            )
        ));
        
        $this->add(array(
            'name' => 'Nganh',
            'type' => '\Zend\Form\Element\Select',
            'label' => 'Chọn ngành',
            'attributes' => array(
                "required" => "required",
                'class' => "span6",
                'data-placeholder' => "Chọn ngành"
            ),
            'option' => array(
                'label' => 'Chọn ngành...',
                'value_options' => array()
            )
            
        ));
        
        $this->add(array(
            'name' => 'Nghe',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span6"
            )
        ));
        
        $this->add(array(
            'name' => 'CanBoQuanLy',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                
                'class' => "span6",
                'type' => "text",
                'placeholder' => "",
                'disabled' => " "
            )
        ));
        
        $this->add(array(
            'name' => 'DoiThue',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
               
                'class' => "span6",
                'type' => "text",
                'placeholder' => "",
                'disabled' => " "
            )
        ));
        
        $this->add(array(
            'name' => 'Submit',
            'type' => '\Zend\Form\Element\Submit',
            
            'attributes' => array(
                
                'class' => "btn btn-success",
                'value' => 'Submit'
            )
        ));
        
        $this->add(array(
            'name' => 'HanhDong',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                
            )
        ));
    }
}

?>