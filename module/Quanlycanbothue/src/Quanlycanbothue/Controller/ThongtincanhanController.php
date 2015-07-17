<?php
namespace Quanlycanbothue\Controller;

use Application\base\baseController;


class ThongtincanhanController extends baseController
{

    public function indexAction()
    {
        return array(
            'user' => $this->getUser()
        );
    }

    public function doimatkhauAction()
    {
        
    }


}