<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Application\Acl\Acl;
use Application\Entity\user;
use Zend\View\HelperPluginManager;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function caidatLayout(MvcEvent $e)
    {
        
        // get router
        $routerMatch = $e->getRouteMatch();
        
        $StringUrlController = $routerMatch->getParam('controller');
        
        $StringUrlAction = $routerMatch->getParam('action');
        
        // get $namespace
        $NameSpace = explode('\\', $StringUrlController)[0];
        // get $controller
        $controllerName = explode('\\', $StringUrlController)[2];
        // get action
        $actionName = strtolower($StringUrlAction);
        
        // get config
        $config = $e->getApplication()
            ->getServiceManager()
            ->get('config');
        
        // get controller
        $controller = $e->getTarget();
        // echo $config['layouts'][$NameSpace]['controllers'][$controllerName]['actions'][$actionName];
        // action
        if (isset($config['layouts'][$NameSpace]['controllers'][$controllerName]['actions'][$actionName])) {
            $controller->layout($config['layouts'][$NameSpace]['controllers'][$controllerName]['actions'][$actionName]);
        } // default controller
elseif (isset($config['layouts'][$NameSpace]['controllers'][$controllerName]['default'])) {
            $controller->layout($config['layouts'][$NameSpace]['controllers'][$controllerName]['default']);
        } // default module
elseif (isset($config['layouts'][$NameSpace]['default'])) {
            $controller->layout($config['layouts'][$NameSpace]['default']);
        }
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        //
        
        // sharedManager
        $esm = $e->getApplication()
            ->getEventManager()
            ->getSharedManager();
        // cai dat layout
        $esm->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', array(
            $this,
            'caidatLayout'
        ), 10);
        
        $application = $e->getApplication();
        $em = $application->getEventManager();
        $em->attach('route', array(
            $this,
            'onRoute'
        ), - 100);
    }
    
    // WORKING the main engine for ACL
    public function onRoute(\Zend\EventManager\EventInterface $e) // Event manager of the app
    {
        $application = $e->getApplication();
        $routeMatch = $e->getRouteMatch();
        $sm = $application->getServiceManager();
        
        $auth = $sm->get('Zend\Authentication\AuthenticationService');
        
        $config = $sm->get('Config');
        
        $acl = new Acl($config);
        
        // everyone is guest untill it gets logged in
        $role = Acl::DEFAULT_ROLE; // The default role is guest $acl
        
        $controller = $routeMatch->getParam('controller');
        
        $action = $routeMatch->getParam('action');
        
        if ($auth->hasIdentity()) {
            
            $usr = $auth->getIdentity();
            if($usr instanceof user){
                /* @var $usr user */
                $usrl_id = $usr->getLoaiUser(); // Use a view to get the name of the role
                // TODO we don't need that if the names of the roles are comming from the DB
                switch ($usrl_id) {
                    case 0:
                        $role = Acl::DEFAULT_ROLE; // khack
                        break;
                    case 1:
                        $role = 'admin';
                        break;
                    case 2:
                        $role = 'chicuctruong';
                        break;
                    case 3:
                        $role = 'doitruong';
                        break;
                    case 4:
                        $role = 'canbothue';
                        break;
                    default:
                        $role = Acl::DEFAULT_ROLE; // khach
                        break;
                }
                
                // user khong co quyen truy cap se dc chuyen sang trang 'khong co quyen truy cap '
            }
            else{
                $role = Acl::DEFAULT_ROLE; // khack
            }
            
        }
        
        if (! $acl->hasResource($controller)) {
            throw new \Exception('Resource ' . $controller . ' not defined');
        }
        
        // chuyen ve router home neu khong dc phep truy cap
        if (! $acl->isAllowed($role, $controller, $action)) {
            if ($auth->hasIdentity()) {
                $url = $e->getRouter()->assemble(array(), array(
                    'name' => 'home/deny'
                ));
                
                $response = $e->getResponse();
                
                $response->getHeaders()->addHeaderLine('Location', $url);
                // The HTTP response status code 302 Found is a common way of performing a redirection.
                // http://en.wikipedia.org/wiki/HTTP_302
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit();
            } else {
                $url = $e->getRouter()->assemble(array(), array(
                    'name' => 'home'
                ));
                
                $response = $e->getResponse();
                
                $response->getHeaders()->addHeaderLine('Location', $url);
                // The HTTP response status code 302 Found is a common way of performing a redirection.
                // http://en.wikipedia.org/wiki/HTTP_302
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit();
            }
        }
    }

    public function getViewHelperConfig()
    {
        // $auth = $this->sm->get('Zend\Authentication\AuthenticationService');
        // $config = $this->sm->get('Config');
        // $acl = new Acl($config);
        return array(
            'factories' => array(
                // This will overwrite the native navigation helper
                'navigation' => function (HelperPluginManager $pm) {
                    $sm = $pm->getServiceLocator();
                    $config = $sm->get('Config');
                    
                    // Setup ACL:
                    // We have our own class.
                    // ToDo think for better place for it maybe in CsnBase. I have it in CsnAuthorize and here \CsnNavigation\Acl\Acl
                    $acl = new Acl($config);
                    
                    // $acl = new Acl($config);
                    // $acl->addRole(new GenericRole('member'));
                    // $acl->addRole(new GenericRole('admin'));
                    // $acl->addResource(new GenericResource('mvc:admin'));
                    // $acl->addResource(new GenericResource('mvc:community.account'));
                    // $acl->allow('member', 'mvc:community.account');
                    // $acl->allow('admin', null);
                    
                    // $acl->addResource(new GenericResource('AuthDoctrine\Controller\Index'));
                    // $acl->allow('member', 'AuthDoctrine\Controller\Index');
                    
                    // Get the AuthenticationService
                    $auth = $sm->get('Zend\Authentication\AuthenticationService');
                    $role = Acl::DEFAULT_ROLE; // The default role is guest $acl
                                               // With Doctrine
                    if ($auth->hasIdentity()) {
                        /* @var $user user */
                        $user = $auth->getIdentity();
                        $usrlId = $user->getLoaiUser(); // Use a view to get the name of the role
                                                        // TODO we don't need that if the names of the roles are comming from the DB
                        switch ($usrlId) {
                            case 0:
                                $role = Acl::DEFAULT_ROLE; // khach
                                break;
                            case 1:
                                $role = 'admin';
                                break;
                            case 2:
                                $role = 'chicuctruong';
                                break;
                            case 3:
                                $role = 'doitruong';
                                break;
                            case 4:
                                $role = 'canbothue';
                                break;
                            default:
                                $role = Acl::DEFAULT_ROLE; // khach
                                break;
                        }
                    }
                    
                    // Get an instance of the proxy helper
                    $navigation = $pm->get('Zend\View\Helper\Navigation');
                    
                    // Store ACL and role in the proxy helper:
                    $navigation->setAcl($acl)->setRole($role); // 'member'
                                                               
                    // Return the new navigation helper instance
                    return $navigation;
                }
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            // - 'aliases' => array( // !!! aliases not alias
            // - 'Zend\Authentication\AuthenticationService' => 'doctrine_authenticationservice', // aliases can be overwriten
            // - ),
            'factories' => array(
                // taken from DoctrineModule on GitHub
                // Please note that Iam using here a Zend\Authentication\AuthenticationService name, but it can be anything else
                // However, using the name Zend\Authentication\AuthenticationService will allow it to be recognised by the ZF2 view helper.
                // the configuration of doctrine.authenticationservice.orm_default is in module.config.php
                'Zend\Authentication\AuthenticationService' => function ($serviceManager) {
                    // - 'doctrine_authenticationservice' => function($serviceManager) {
                    // If you are using DoctrineORMModule:
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                    
                    // If you are using DoctrineODMModule:
                    // - return $serviceManager->get('doctrine.authenticationservice.odm_default');
                },
                // Add this for SMTP transport
                // ToDo move it ot a separate module CsnMail
                'mail.transport' => function (ServiceManager $serviceManager) {
                    $config = $serviceManager->get('Config');
                    $transport = new Smtp();
                    $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
                    return $transport;
                },
                'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
            )
        );
    }
}
