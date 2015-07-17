<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;
use Application\Entity\dukienthue;

class dukienthuecuanamModel extends baseModel
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
 * @param string $nam
 * @param user $user
 * @return \Application\Entity\ketqua  */
    public function dsdukienthuecuanam($nam,$user)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'dukienthue',
                    'nguoinopthue',
                    'usernnts'
                ))
                ->from('Application\Entity\dukienthue', 'dukienthue')
                ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                
                ->where('dukienthue.KyThue = ?1')
                ->andWhere('usernnts.user = ?2')
                ->andWhere('usernnts.ThoiGianKetThuc is null')
                ->setParameter(2, $user)
                ->setParameter(1, $nam);
            } else
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukienthue',
                        'nguoinopthue',
                        'usernnts'
                    ))
                    ->from('Application\Entity\dukienthue', 'dukienthue')
                    ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('dukienthue.KyThue = ?1')
                    ->andWhere('user.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(2, $user)
                    ->setParameter(1, $nam);
                }
            
            $this->kq->setKq(true);
            $this->kq->setObj($q->getQuery()
                ->getResult());
            $this->kq->setMessenger('Lấy danh sách dự thuế của năm ' . $nam . ' thành công !');
            return $this->kq;
            
        } catch (\Exception $e) {
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }
    
    /**
     *
     * @param string $nam
     * @param user $user
     * @return \Application\Entity\ketqua  */
    public function dsDKTNJson($nam,$user)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'dukienthue',
                    'nguoinopthue',
                    'usernnts',
                    'user'
                ))
                ->from('Application\Entity\dukienthue', 'dukienthue')
                ->join('dukienthue.user','user')
                ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
    
                ->where('dukienthue.KyThue = ?1')
                ->andWhere('usernnts.user = ?2')
                ->andWhere('usernnts.ThoiGianKetThuc is null')
                ->setParameter(2, $user)
                ->setParameter(1, $nam);
            } else
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukienthue',
                        'nguoinopthue',
                        'usernnts',
                        'user1'
                    ))
                    ->from('Application\Entity\dukienthue', 'dukienthue')
                    ->join('dukienthue.user','user1')
                    ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('dukienthue.KyThue = ?1')
                    ->andWhere('user.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(2, $user)
                    ->setParameter(1, $nam);
                }
    
            $this->kq->setKq(true);
            $this->kq->setObj($q->getQuery()
                ->getArrayResult());
            $this->kq->setMessenger('Lấy danh sách dự thuế của năm ' . $nam . ' thành công !');
            return $this->kq->toArray();
    
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }

    /**
     *
     * @param string $nam            
     * @param string $masothue            
     * @param string $user 
     * @param string $tieumuc            
     * @return ketqua
     */
    public function findByID_($nam, $masothue,$tieumuc)
    {
        /* @var $user user */
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
            
            $qb->select('dukienthue')
                ->from('Application\Entity\dukienthue', 'dukienthue')
                ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                ->join('dukienthue.muclucngansach', 'muclucngansach')
                ->where('nguoinopthue.MaSoThue = ?1')
                ->andWhere('muclucngansach.TieuMuc = ?2')
                ->andWhere('dukienthue.KyThue = ?3')
                ->setParameter(3, $nam)
                
                ->setParameter(1, $masothue)
                ->setParameter(2, $tieumuc);
            
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
