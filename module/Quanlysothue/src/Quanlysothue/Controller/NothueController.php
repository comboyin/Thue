<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\nothueModel;


class NothueController extends baseController
{

    public function indexAction()
    {
        $nothueModel = new nothueModel($this->getEntityManager());
        $temp = explode('/', (new \DateTime())->format('d/m/Y'));
        $Thang = $temp[1] . '/' . $temp[2];
        
        $dsNoThue = $nothueModel->dsNoThue($Thang, $this->getUser(), 'array')
            ->getObj();
        
        return array(
            'dsNoThue' => $dsNoThue
        );
    }

    public function dsNoThueAction()
    {
        $Thang = $this->getRequest()
            ->getQuery()
            ->get('Thang');
        $model = new nothueModel($this->getEntityManager());
        echo json_encode($model->dsNoThue($Thang, $this->getUser(), 'array')
            ->toArray());
        return $this->response;
    }
}

?>