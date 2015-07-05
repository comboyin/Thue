<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\nguoinopthue;
use Application\Entity\ketqua;
class thongtinngungnghiModel extends baseModel
{
    public function danhSachThongTinNgungNghi($MaSoThue){
            $kq = new ketqua();
        try {
            $qb = $this->em->createQueryBuilder();
            
            $danhsach = $qb->select('thongtinngungnghi')
                            ->from('Application\Entity\thongtinngungnghi', 'thongtinngungnghi')
                                ->join('thongtinngungnghi.nguoinopthue', 'nguoinopthue')
                                ->where("nguoinopthue.MaSoThue = ?1")
                                ->setParameter(1, $MaSoThue)->getQuery()->getArrayResult();
            
            $kq->setObj($danhsach);
            $kq->setKq(true);
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
        }
        
        return $kq;
    }
    
    
}

?>