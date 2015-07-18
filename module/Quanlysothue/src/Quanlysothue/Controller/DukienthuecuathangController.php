<?php
namespace Quanlysothue\Controller;

use Application\base\baseController;
use Quanlysothue\Models\dukienthuecuanamModel;
use Application\Forms\UploadForm;


class DukienthuecuathangController extends baseController
{

    public function indexAction()
    {
        
        
        
        $formUp = new UploadForm('upload-form');
        // Lấy năm gần nhất
        // to String 'Y'
        $today = (new \DateTime())->format('m-Y');
        
        $dukienthuecuanamModel = new dukienthuecuanamModel($this->getEntityManager());
        
        // danh sach theo nam
        $dsdkthuecuanam = $dukienthuecuanamModel->dsdukienthuecuanam($today, $this->getUser());
        
        return array(
            'formUp' => $formUp,
            'dsDuKienThueCuaThang' => $dsdkthuecuanam->getObj()
        );
    }
}

?>