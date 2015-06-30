<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\dukientruythu;
class dukientruythuModel extends baseModel
{
    /**
     * 
     * @param ArrayCollection $arrayConlectionTruyThu
     * @return \Application\Entity\ketqua  */   
    public function PersitArrayTruyThu($arrayConlectionTruyThu){
        $kq = new ketqua();
        
        $nganhModel = new nganhModel($this->em);
        try {
            $dem = 0 ;
            for ($i = 0 ; $i<$arrayConlectionTruyThu->count();$i++){
               
                /* @var $truythu dukientruythu */
                $truythu = $arrayConlectionTruyThu->get($i);
                $MaSoThue = $truythu->getNguoinopthue()->getMaSoThue();
                $TieuMuc = $truythu->getMuclucngansach()->getTieuMuc();
                
                $nguoinopthue = $this->em->find('Application\Entity\nguoinopthue', $MaSoThue);
                $muclucngansach = $this->em->find('Application\Entity\muclucngansach', $TieuMuc);
                $tyLeTinhThue = $nganhModel->getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue, $TieuMuc);
                $truythu->setTiLeTinhThue($tyLeTinhThue);
                $truythu->setNguoinopthue($nguoinopthue);
                $truythu->setMuclucngansach($muclucngansach);
                
                $this->em->persist($truythu);
                
                $dem++;
            }
            $this->em->flush();
            
            
            
            $kq->setKq(true);
            $kq->setMessenger("Đã thêm ".$dem.' truy thu thành công !');
            return $kq;
        } catch (\Exception $e) {
            
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
}

?>