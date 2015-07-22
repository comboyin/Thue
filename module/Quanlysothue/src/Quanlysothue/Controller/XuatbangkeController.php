<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Quanlysothue\Excel\Xuatbangke;
use Doctrine\Common\Collections\ArrayCollection;

class XuatbangkeController extends baseController
{

    public function indexAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        
        $dsnguoinopthue = $nguoinopthueModel->DanhSachByIdentity($this->getUser(), 'object')
            ->getObj();
        
        return array(
            'dsnnt' => $dsnguoinopthue
        );
    }

    public function testAction()
    {
        $mod = new Xuatbangke($this->getEntityManager());

        $array = new ArrayCollection();
        
        $bangke = $mod->phatsinh('0301528964', '07/2015');
        $array->add($bangke);
        $bangkesono = $mod->sono('0301528964', '06/2015');
        foreach ($bangkesono->getValues() as $b){
            $array->add($b);
        }
        
        $mod->TaoZipNhieuBangKe($array);
        
        return $this->response;
    }
}

?>