<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;

class thuekhoanModel extends baseModel
{

    public function dsThueKhoan($thang,$user,$type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'thue',
                    'nguoinopthue',
                    'usernnts'
                    
                ))
                ->from('Application\Entity\thue', 'thue')
                ->join('thue.nguoinopthue', 'nguoinopthue')
                
                
                ->join('nguoinopthue.usernnts', 'usernnts')
        
                ->where('thue.KyThue = ?1')
                ->andWhere('usernnts.user = ?2')
                ->andWhere('usernnts.ThoiGianKetThuc is null')
                ->setParameter(2, $user)
                ->setParameter(1, $thang);
            } else
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'thue',
                        'nguoinopthue',
                        'usernnts'
                        
                    ))
                    ->from('Application\Entity\thue', 'thue')
                    ->join('thue.nguoinopthue', 'nguoinopthue')
                    
                    
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('thue.KyThue = ?1')
                    ->andWhere('user.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(2, $user)
                    ->setParameter(1, $thang);
                }
            $this->kq->setKq(true);
            if($type=='array')
            {
                $this->kq->setObj($q->getQuery()
                    ->getArrayResult());
            }
            else if($type=='object'){
                $this->kq->setObj($q->getQuery()
                    ->getResult());
            }
            
           
            $this->kq->setMessenger('Lấy danh sách dự thuế của tháng ' . $thang . ' thành công !');
            return $this->kq;
        
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }

}

?>