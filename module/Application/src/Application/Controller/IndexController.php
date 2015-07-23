<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Application\base\baseController;
use Application\Models\doithueModel;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Tools\SchemaTool;



class IndexController extends baseController
{

    public function indexAction()
    {
        

        $kq = $userModel->getDanhSach();
        var_dump($kq);
        return new ViewModel(array(
            'kq' => $kq
        ));
    }

    public function createschemaAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $tool = new SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()
            ->getAllMetadata());
        var_dump($tool);
        return $this->response;
    }

    public function updateschemaAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $tool = new SchemaTool($em);
        $tool->updateSchema($em->getMetadataFactory()
            ->getAllMetadata());
        var_dump($tool);
        return $this->response;
    }
    
    public function testAction(){
        $dateBir = \DateTime::createFromFormat('d-m-Y','03-11-1992');
        $today = new \DateTime();
        var_dump(floor(strtotime('1992-07-23')/(365*60*60*24)) ) ;
        return $this->response;

        
    }
}
