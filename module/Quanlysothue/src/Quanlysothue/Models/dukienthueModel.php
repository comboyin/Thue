<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;

class dukienthueModel extends baseModel
{

    public function xoa($id)
    {}

    public function findById($id)
    {}



    public function getDanhSach()
    {}

    public function sua($id)
    {}

    /**
     * 
     * @param user $user  */
    public function getDanhSachByIdentity($user)
    {
        try {
            $qb = $this->em->createQueryBuilder();
            $qb->select(array())
                ->from('Application\Entity\dukienthue', 'dukienthue');
            
            $this->kq->setObj($qb->getQuery()->getResult());
            
            if($this->kq->getObj()!=null)
            {
                $this->kq->setMessenger('Lấy danh sách thành công !');
                $this->kq->setKq(true);
            }
            else {
                $this->kq->setMessenger('Kết quả không như mong muốn !');
                $this->kq->setKq(false);
            }
            
            return $this->kq;
        } catch (\Exception $e) {
            var_dump($e);
        }
    }



}

?>