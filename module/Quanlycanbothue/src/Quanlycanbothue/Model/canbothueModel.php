<?php

namespace Quanlycanbothue\Model;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;

class canbothueModel extends baseModel
{
    public function xoa($id)
    {}
    
    public function findById($id)
    {}
    
    public function them($obj)
    {
        try {
            $this->em->persist($obj);
            $this->em->flush();
            $kq = new ketqua();
            $kq->setKq(true);
            $kq->setMessenger('Thêm thành công !');
    
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    public function getDanhSach()
    {}
    
    /**
     * @param dukienthue $obj
     */
    public function sua($obj)
    {
        try {
            $kq = new ketqua();
            $this->em->merge($obj);
            $this->em->flush();
            $kq->setKq(true);
            $kq->setMessenger("Cập nhập thành công !");
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    
    
    /**
     *
     * @param user $user
     * @return \Application\Entity\ketqua  */
    public function dscanbothue($user)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if($user->getLoaiUser() == 3){
                $q->select(array('canbothue'))
                  ->from('Application\Entity\user', 'canbothue')
                  ->where('canbothue.parentUser = ?1')
                  ->setParameter(1, $user);
                
                $this->kq->setKq(true);
                $this->kq->setObj($q->getQuery()
                    ->getResult());
                $this->kq->setMessenger('Lấy danh sách cán bộ thuế thành công !');
            } else {
                $this->kq->setKq(false);
                $this->kq->setMessenger('Lỗi: Không có quyền truy cập!');
            }
            return $this->kq;
        } catch (\Exception $e) {
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
        
    }
    
    /**
     *
     * Kiểm tra xem ma cbt có thuộc quyền quản lý của user hay không
     *
     * @param string $ma
     * @param user $user
     * 
     * @return boolean
     */
    public function ktCBT($ma, $user)
    {
        $ds = $this->dscanbothue($user)->getObj();
        if ($ds != null) {
            foreach ($ds as $cbt) {
                if ($cbt->getMaUser() == $ma)
                    return true;
            }
        } else {
            return false;
        }
    }
    
    /**
     *
     * Kiểm tra xem email cbt có thuộc quyền quản lý của user hay không
     *
     * @param string $email
     * @param user $user
     *
     * @return boolean
     */
    public function ktCBT2($email, $user)
    {
        $ds = $this->dscanbothue($user)->getObj();
        if ($ds != null) {
            foreach ($ds as $cbt) {
                if ($cbt->getEmail() == $email)
                    return true;
            }
        } else {
            return false;
        }
    }
    
    /**
     *
     * @param string $ma
     * @return ketqua
     */
    public function findByID_($ma)
    {
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
    
             $qb->select(array('canbothue'))
                  ->from('Application\Entity\user', 'canbothue')
                  ->where('canbothue.MaUser = ?1')
                  ->setParameter(1, $ma);
    
            $kq->setObj($qb->getQuery()
                ->getSingleResult());
            $kq->setKq(true);
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    /**
     * ajax
     * @param string $ma
     * @return ketqua
     */
    public function findByID_aj($ma)
    {
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
    
            $qb->select(array('canbothue'))
            ->from('Application\Entity\user', 'canbothue')
            ->where('canbothue.MaUser = ?1')
            ->setParameter(1, $ma);
    
            $kq->setObj($qb->getQuery()
                ->getArrayResult());
            $kq->setKq(true);
            return $kq->toArray();
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq->toArray();
        }
    }
    
    /**
     *
     * @param string $email
     * @return ketqua
     */
    public function findByEmail_($email)
    {
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
    
            $qb->select(array('canbothue'))
            ->from('Application\Entity\user', 'canbothue')
            ->where('canbothue.Email = ?1')
            ->setParameter(1, $email);
    
            $kq->setObj($qb->getQuery()
                ->getSingleResult());
            $kq->setKq(true);
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    /**
     *
     * @param string $email
     * @param string $MaUser
     * @return ketqua
     */
    public function findByEmail2_($email, $MaUser)
    {
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
    
            $qb->select(array('canbothue'))
            ->from('Application\Entity\user', 'canbothue')
            ->where('canbothue.Email = ?1')
            ->andWhere('canbothue.MaUser not like ?2')
            ->setParameter(1, $email)
            ->setParameter(2, $MaUser);
    
            $kq->setObj($qb->getQuery()
                ->getSingleResult());
            $kq->setKq(true);
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
}