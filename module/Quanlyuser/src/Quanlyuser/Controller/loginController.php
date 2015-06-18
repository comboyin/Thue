<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Quanlyuser for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Quanlyuser\Controller;

use Application\base\baseController;
use Zend\View\Model\ViewModel;
use Quanlyuser\Form\LoginForm;
use Application\Entity\user;

class loginController extends baseController
{
    
    // login
    public function indexAction()
    {
        
        //KIEM TRA XEM DA DANG NHAP CHUA
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        
        if ($authService->hasIdentity()) {
            /* @var $identity user */
            
            $identity = $authService->getIdentity();

            $this->loginthanhcong();
            //return $this->redirect()->toRoute('quanlyuser',array('controller'=>'Login','action'=>'foo'));
        }

        $form = new LoginForm();
        $form->get('submit')->setValue('Login');
        
        $messages = null;
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            
            // Filters have been fixed
            $user = new user();
            
            $form->setInputFilter($user->getInputFilter());
            
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                
                $data = $form->getData();
                // $data = $this->getRequest()->getPost();
                
                // If you used another name for the authentication service, change it here
                // it simply returns the Doctrine Auth. This is all it does. lets first create the connection to the DB and the Entity
                
                // Do the same you did for the ordinar Zend AuthService
                $adapter = $authService->getAdapter();
                
                $login = $data['MaUser'];
                
                if (strpos($login,'@') !== false) {
                    // use email identity property
                    $adapter->getOptions()->setIdentityProperty('Email');
                    
                }
                
                $adapter->setIdentityValue($login); // $data['usr_name']
                $adapter->setCredentialValue($data['MatKhau']); // $data['usr_password']
                $authResult = $authService->authenticate();
                // echo "<h1>I am here</h1>";
                if ($authResult->isValid()) {
                    $user = $authResult->getIdentity();
                    $authService->getStorage()->write($user);
                    $time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
                            // - if ($data['rememberme']) $authService->getStorage()->session->getManager()->rememberMe($time); // no way to get the session
                    if ($data['remenber'] == 1) {
                        $sessionManager = new \Zend\Session\SessionManager();
                        $sessionManager->rememberMe($time);
                    }  
                    // - return $this->redirect()->toRoute('home');
                    //chuyen sang trang dang nhap thanh cong
                    //return $this->redirect()->toRoute('quanlyuser',array('controller'=>'Login','action'=>'foo'));
                    return $this->loginthanhcong();
                }
                foreach ($authResult->getMessages() as $message) {
                    $messages .= "$message\n";
                }
                
                /*
                 * $identity = $authenticationResult->getIdentity();
                 * $authService->getStorage()->write($identity);
                 *
                 * $authenticationService = $this->serviceLocator()->get('Zend\Authentication\AuthenticationService');
                 * $loggedUser = $authenticationService->getIdentity();
                 */
            }
        }
        
        return new ViewModel(array(
            
            'form' => $form,
            'messages' => $messages
        ));
    }

    public function logoutAction()
    {
        
        // in the controller
        // $auth = new AuthenticationService();
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        
        // @todo Set up the auth adapter, $authAdapter
        
        if ($auth->hasIdentity()) {
            
            $identity = $auth->getIdentity();
            
        }
        $auth->clearIdentity();
        // - $auth->getStorage()->session->getManager()->forgetMe(); // no way to get to the sessionManager from the storage
        $sessionManager = new \Zend\Session\SessionManager();
        $sessionManager->forgetMe();
        
        return $this->redirect()->toRoute('quanlyuser', array(
            'controller' => 'login',
            'action' => 'index'
        ));
    }

    
    public function loginthanhcong()
    {
         return $this->redirect()->toRoute('quanlyuser', array(
            'controller' => 'menu',
            'action' => 'index'
        ));
    }
    
    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/foo
        // in the controller
        // $auth = new AuthenticationService();
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        
        // @todo Set up the auth adapter, $authAdapter
        
        if ($auth->hasIdentity()) {
        /* @var $identity user */
            $identity = $auth->getIdentity();
            return array('canbothue' =>$identity->getTenUser());
        }
        return array('canbothue' =>'BI LOI');
        
    }
    
    public function denyAction()
    {
        
        return new ViewModel();
    }
}
