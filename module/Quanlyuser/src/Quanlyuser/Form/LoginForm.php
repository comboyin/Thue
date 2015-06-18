<?php
namespace Quanlyuser\Form; 
use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'MaUser',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Nhập tên đăng nhập',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Tên đăng nhập',
            ),
        ));

        $this->add(array(
            'name' => 'MatKhau',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'placeholder' => 'Nhập Password ...',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Mật khẩu',
            ),
        ));

        $this->add(array(
            'name' => 'remenber',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'options' => array(
                'label' => 'Ghi nhớ đăng nhập',
            )
        ));

        /* $this->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
        )); */
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Login'
            ),
        ));
    }
}