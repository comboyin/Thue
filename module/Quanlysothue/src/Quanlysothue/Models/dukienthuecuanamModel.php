<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;
use Application\Entity\dukientruythu;

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
     * @param dukientruythu $obj
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
                    ->andWhere('user.coquanthue = ?2')
                    ->setParameter(2, $user->getCoquanthue())
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
     * @param string $kythue            
     * @param string $masothue            
     * @return ketqua
     *
     */
    public function xoadukientruythu($kythue, $masothue)
    {
        try {
            $nguoinopthue = $this->em->find('Application\Entity\nguoinopthue', $masothue);
            $kq = new ketqua();
            if ($nguoinopthue == null) {
                $kq->setKq(false);
                $kq->setMessenger('Không tìm thấy người nộp thuế cố mã số thuế ' . $masothue);
                return $kq;
            } else {
                $qb = $this->em->createQueryBuilder();
                
                $qb->delete('Application\Entity\dukientruythu', 'd')
                    ->where('d.nguoinopthue = :nguoinopthue')
                    ->andWhere('d.KyThue = :KyThue')
                    ->setParameter(':nguoinopthue', $nguoinopthue)
                    ->setParameter(':KyThue', $kythue);
                $d = $qb->getQuery()->execute();
                
                if ($d == 1) {
                    $kq->setKq(true);
                    $kq->setMessenger("Xóa thành công doanh số dự kiến có mã số thuế $masothue và kỳ thuế $kythue");
                }
                return $kq;
            }
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     *
     * @param string $kythue            
     * @param string $masothue            
     * @param string $user 
     * @param string $tieumuc            
     * @return ketqua
     */
    public function findByID_($kythue, $masothue,$tieumuc)
    {
        /* @var $user user */
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
            
            $qb->select('dukientruythu')
                ->from('Application\Entity\dukientruythu', 'dukientruythu')
                ->join('dukientruythu.nguoinopthue', 'nguoinopthue')
                ->join('dukientruythu.muclucngansach', 'muclucngansach')
                ->where('nguoinopthue.MaSoThue = ?1')
                ->andWhere('muclucngansach.TieuMuc = ?2')
                ->andWhere('dukientruythu.KyThue = ?3')
                ->setParameter(3, $kythue)
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

    /**
     * Xóa nhiều dự kiến doanh số 1 lúc
     * 
     * @param string $kythue            
     * @param array $masothuedata            
     * @param user $user            
     */
    public function xoanhieuDKDS($kythue, $masothuedata, $user)
    {
        try {
            $dem = 0;
            foreach ($masothuedata as $mst) {
                $kq = $this->xoadukientruythu($kythue, $mst);
                if($kq->getKq()==true)
                {
                    $dem++;
                }
            }
            
            $this->kq->setKq(true);
            $this->kq->setMessenger("Đã xóa $dem dự kiến doanh số của kỳ thuế $kythue");
            return $this->kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    
   
}
