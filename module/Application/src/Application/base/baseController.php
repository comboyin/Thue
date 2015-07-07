<?php
namespace Application\base;

use Zend\Mvc\Controller\AbstractActionController;
use Application;
use Doctrine\ORM\EntityManager;
use Application\Entity\user;
use Zend\Form\Form;

class baseController extends AbstractActionController
{

    /**
     *
     * @var EntityManager
     *
     */
    protected $em = null;

    /**
     *
     * @return EntityManager
     *
     */
    protected function getEntityManager()
    {
        if ($this->em == null) {
            return $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    /**
     *
     * @return user|null
     *
     *
     */
    protected function getUser()
    {
        // KIEM TRA XEM DA DANG NHAP CHUA
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        
        if ($authService->hasIdentity()) {
            /* @var $identity user */
            
            $identity = $authService->getIdentity();
            
            return $identity;
            // return $this->redirect()->toRoute('quanlyuser',array('controller'=>'Login','action'=>'foo'));
        }
        return null;
    }

    /**
     *
     * @param Form $form            
     */
    protected function getErrorMessengerForm($form)
    {
        $mss = "";
        // var_dump($form->getElements());
        foreach ($form->getElements() as $Element) {
            /* @var $value Element */
            
            if (count($Element->getMessages()) > 0) {
                $mss .= $Element->getName() . ":\n";
                foreach ($Element->getMessages() as $mess) {
                    
                    $mss .= "   + " . $mess . "\n";
                }
            }
        }
        
        return $mss;
    }


}

?>