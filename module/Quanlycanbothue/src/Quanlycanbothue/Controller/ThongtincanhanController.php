<?php
namespace Quanlycanbothue\Controller;

use Application\base\baseController;
use Application\Entity\ketqua;
use Application\Entity\user;
use Quanlycanbothue\Model\canbothueModel;

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
        $kq = null;
        if ($this->getRequest()
            ->getPost()
            ->get('MatKhauCu') != null)
        {
            $kq = new ketqua();
            $model = new canbothueModel($this->getEntityManager());
            $request = $this->getRequest();
            $post = $request->getPost();
            $MatKhauCu = $post->get('MatKhauCu');
            $MatKhau = $post->get('MatKhau');
            if(md5($MatKhauCu) === $this->getUser()->getMatKhau())
            {
                $MaUser = $this->getUser()->getMaUser();
                $usere = $model->findByID_($MaUser)->getObj();
                if ($usere != null) {
                    
                    $usere->setMatKhau(md5($MatKhau));
                    $kq = $model->merge($usere);
                    $kq->setKq(true);
                    $kq->setMessenger("Đổi Password thành công!");
                }
                else
                {
                    $kq->setKq(false);
                    $kq->setMessenger("Không tìm thấy Cán Bộ Thuế này !");
                }
               
            }
            else
            {
                $kq->setKq(false);
                $kq->setMessenger("Nhập sai Password hiện tại!");
            }
            
        }
        
        return array(
            'kq' => $kq
        );
    }


}