<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;

class chatModel extends baseModel
{

    /**
     * Trả về danh sách chat
     * @param user $user
     * @return \Application\Entity\ketqua  */
    public function DanhSachChat($user)
    {
        $kq = new ketqua();
        
        try {
        
            $obj = $qb = $this->em->createQueryBuilder()->
            select(array('chat', 'user'))
            ->from('Application\Entity\chat', 'chat')
            ->join('chat.user', 'user')
            ->join('user.coquanthue', 'coquanthue')
            ->where('coquanthue = ?1')
            ->setParameter(1,$user->getCoquanthue())
            ->addOrderBy('chat.Time', 'ASC')
            ->getQuery()
            ->getResult();
            if(count($obj)>0){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Lấy danh sách chat thành công !");
            }
            else{
                $kq->setObj($obj);
                $kq->setKq(false);
            }
            
            return $kq;
        } catch (\Exception $e) {

            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

}

