<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;
use Application\Entity\dukienmb;

class dukienthuemonbaiModel extends baseModel
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
     * @param dukienmb $obj
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
    public function dsdukienthuemonbai($nam,$user)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'dukienmb',
                    'nguoinopthue',
                    'usernnts'
                ))
                ->from('Application\Entity\dukienmb', 'dukienmb')
                ->join('dukienmb.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                
                ->where('dukienmb.Nam = ?1')
                ->andWhere('usernnts.user = ?2')
                ->andWhere('usernnts.ThoiGianKetThuc is null')
                ->setParameter(2, $user)
                ->setParameter(1, $nam);
            } else
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukienmb',
                        'nguoinopthue',
                        'usernnts'
                    ))
                    ->from('Application\Entity\dukienmb', 'dukienmb')
                    ->join('dukienmb.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('dukienmb.Nam = ?1')
                    ->andWhere('user.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(2, $user)
                    ->setParameter(1, $nam);
                }
            
            $this->kq->setKq(true);
            $this->kq->setObj($q->getQuery()
                ->getResult());
            $this->kq->setMessenger('Lấy danh sách dự môn bài của năm ' . $nam . ' thành công !');
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
    public function dsDKTMBJson($nam,$user)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'dukienmb',
                    'nguoinopthue',
                    'usernnts',
                    'muclucngansach'
                ))
                ->from('Application\Entity\dukienmb', 'dukienmb')
                ->join('dukienmb.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->join('dukienmb.muclucngansach', 'muclucngansach')
                ->where('dukienmb.Nam = ?1')
                ->andWhere('usernnts.user = ?2')
                ->andWhere('usernnts.ThoiGianKetThuc is null')
                ->setParameter(2, $user)
                ->setParameter(1, $nam);
            } else
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukienmb',
                        'nguoinopthue',
                        'usernnts'
                    ))
                    ->from('Application\Entity\dukienmb', 'dukienmb')
                    ->join('dukienmb.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('dukienmb.Nam = ?1')
                    ->andWhere('user.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(2, $user)
                    ->setParameter(1, $nam);
                }
    
            $this->kq->setKq(true);
            $this->kq->setObj($q->getQuery()
                ->getArrayResult());
            $this->kq->setMessenger('Lấy danh sách dự môn bài của năm ' . $nam . ' thành công !');
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
     * @return ketqua
     */
    public function findByID_($nam, $masothue)
    {
        /* @var $user user */
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
            
            $qb->select('dukienmb')
                ->from('Application\Entity\dukienmb', 'dukienmb')
                ->join('dukienmb.nguoinopthue', 'nguoinopthue')
                ->where('nguoinopthue.MaSoThue = ?1')
                ->andWhere('dukienmb.Nam = ?2')
                ->setParameter(2, $nam)
                ->setParameter(1, $masothue);
            
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
