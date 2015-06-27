<?php
namespace Quanlynguoinopthue\Form;

use Zend\Form\Form;

class formThayDoiDiaChiKDNNT extends Form
{

    function __construct()
    {
        parent::__construct('formThayDoiDiaChiKDNNT');
        
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'DiaChiKD',
            'type' => 'Zend\Form\Element\Text',
            'required'=>true,
            'attributes' => array(
                "required" => "required",
                'class' => "span3",
                'placeholder' => "Số nhà, Tên đường"
            )
        ));
        
        $this->add(array(
            'name' => 'ChanLe',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
        
                'style' => "width: 40px",
                'placeholder' => "Chẳn Lẻ"
            )
        ));
        
        
        $this->add(array(
            'name' => 'Hem',
            'type' => 'Zend\Form\Element\Number',
            'attributes' => array(
        
                'class' => "span2",
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
        
                'class' => "span3",
                'placeholder' => "Tên Đường"
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
                'class' => "span3",
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
            'name' => 'Quan',
            'type' => '\Zend\Form\Element\Select',
            'attributes' => array(
                "required" => "required",
                'class' => "span3",
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
            'name' => 'ThoiDiemThayDoi',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'id' => "dp_ThoiDiemThayDoi",
                'type' => "text",
                'value' => "",
                'size' => "16",
                'class' => "m-ctrl-medium popovers",
                'data-trigger' => "hover",
                'data-content' => "Là thời điểm kết thúc cho địa chỉ KD cũ, và là thời gian bắt đầu cho địa chỉ KD mới",
                'data-original-title' => "Thời điểm thay đổi"
            )
        ));
        

        

        $this->add(array(
            'name' => 'SubmitThayDoi',
            'type' => '\Zend\Form\Element\Submit',
            
            'attributes' => array(
                
                'class' => "btn btn-success",
                'value' => 'Thay đổi'
            )
        ));
        
        $this->add(array(
            'name' => 'SubmitCapNhat',
            'type' => '\Zend\Form\Element\Submit',
        
            'attributes' => array(
        
                'class' => "btn btn-success",
                'value' => 'Cập nhật'
            )
        ));
        
        
        $this->add(array(
            'name' => 'HanhDong',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value'=>'ThayDoiDiaChiKD'
            )
        ));
    }
}

?>