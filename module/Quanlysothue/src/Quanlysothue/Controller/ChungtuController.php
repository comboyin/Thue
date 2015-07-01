<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Froms\FormChungTu;
use Application\Models\chungtuModel;
use Application\Entity\chungtu;
use Application\Entity\ketqua;

class ChungtuController extends baseController
{

    public function indexAction()
    {}

    public function themAction()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        /* @var $request Request */
        /* @var $form Form */
        $request = $this->getRequest();
        $kq = new ketqua();
        
        $form = new FormChungTu();
        
        $form->setData($request->getPost());
        
        // validation thanh cong
        if ($form->isValid()) {
            $chungtuModel = new chungtuModel($this->getEntityManager());
            $post = $request->getPost();

            // them
            $NgayChungTu = $post->get('NgayChungTu');
            $NgayHachToan = $post->get('NgayHachToan');
            $SoChungTu = $post->get('SoChungTu');
            $MaSoThue = $post->get('MaSoThue');
            $chungtu = new chungtu();
            $chungtu->setSoChungTu($SoChungTu);
            $chungtu->setNgayHachToan($NgayChungTu);
            $chungtu->setNgayChungTu($NgayHachToan);
            $chungtu->setNguoinopthue($this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue));

            $kq = $chungtuModel->them($chungtu);
        } else { // validation lỗi
            $mss = $this->getErrorMessengerForm($form);
            $kq->setKq(false);
            $kq->setMessenger($kq->getMessenger() . "\n" . $mss);
        }
        
        // trả về json
        echo json_encode($kq->toArray());
        return $this->response;
    }
}

?>