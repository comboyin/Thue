<?php
namespace Quanlynguoinopthue\Form;



use Zend\Form\Form;

class formTTCoBanNNT extends Form
{

    function __construct()
    {
        parent::__construct('formTTCoBanNNT');
        
        $this->setAttribute('method', 'post');
        

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
            'name' => 'Nghe',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                "required" => "required",
                'class' => "span6"
            )
        ));
        


        
        $this->add(array(
            'name' => 'Submit',
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
                'value' =>"CapNhatTTCoBan"
            )
        ));
    }
}

?>