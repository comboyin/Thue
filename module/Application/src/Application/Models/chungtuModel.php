<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;

class chungtuModel extends baseModel
{

    /**
     * param : Y-m-d
     * trả về danh sách (Array) chứng từ giữa 2 thời gian truyền vào
     * @param string $startDate            
     * @param string $endDate            
     * @return \Application\Models\ketqua
     */
    public function DanhSachChungTuGiuaNgay($startDate, $endDate)
    {
        $kq = new ketqua();
        
        try {
            
            $obj = $qb = $this->em->createQueryBuilder()->
                        select(array('chungtu','nguoinopthue'))
                        ->from('Application\Entity\chungtu', 'chungtu')
                        ->join('chungtu.nguoinopthue', 'nguoinopthue')
                        ->where('chungtu.NgayChungTu >= ?1')
                        ->andWhere('chungtu.NgayChungTu <= ?2')
                        ->setParameter(1, $startDate)
                        ->setParameter(2, $endDate)
                        ->getQuery()
                        ->getArrayResult();
            $kq->setObj($obj);
            $kq->setKq(true);
            $kq->setMessenger("Lấy danh sách chứng từ thành công !");
            return $kq;
        } catch (\Exception $e) {
            
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
}

