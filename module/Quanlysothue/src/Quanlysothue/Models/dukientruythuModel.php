<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;
use Application\Entity\dukientruythu;

class dukientruythuModel extends baseModel
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
     *
     * @param dukientruythu $obj
     *            (non-PHPdoc)
     *            
     * @see \Application\base\baseModel::sua()
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
     * @param string $kythue            
     * @param user $user            
     * @return \Application\Entity\ketqua
     */
    public function dsdukiendsbykythue($kythue, $user)
    {
        $q = $this->em->createQueryBuilder();
        try {
            
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'dukientruythu',
                    'nguoinopthue',
                    'usernnts'
                ))
                    ->from('Application\Entity\dukientruythu', 'dukientruythu')
                    ->join('dukientruythu.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('dukientruythu.KyThue = ?1')
                    ->andWhere('usernnts.user = ?2')
                    ->setParameter(2, $user)
                    ->setParameter(1, $kythue);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukientruythu',
                        'nguoinopthue',
                        'usernnts'
                    ))
                        ->from('Application\Entity\dukientruythu', 'dukientruythu')
                        ->join('dukientruythu.nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('dukientruythu.KyThue = ?1')
                        ->andWhere('user.parentUser = ?2')
                        ->setParameter(2, $user)
                        ->setParameter(1, $kythue);
                }
            
            $this->kq->setKq(true);
            $this->kq->setObj($q->getQuery()
                ->getResult());
            $this->kq->setMessenger('Lấy danh sách dự kiến truy thu của kỳ thuế ' . $kythue . ' thành công !');
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
     * @param string $user            
     * @param string $tieumuc            
     * @return ketqua
     */
    public function findByID_($kythue, $masothue, $tieumuc)
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
     * Danh sách dự kiến truy thu theo kỳ thế và của user
     * trả về array ketqua
     * 
     * @param unknown $kythue            
     * @param unknown $user            
     * @return \Application\Entity\ketqua
     */
    public function dsDKTTJson($kythue, $user)
    {
        $q = $this->em->createQueryBuilder();
        try {
            
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'dukientruythu',
                    'nguoinopthue',
                    'usernnts'
                ))
                    ->from('Application\Entity\dukientruythu', 'dukientruythu')
                    ->join('dukientruythu.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('dukientruythu.KyThue = ?1')
                    ->andWhere('usernnts.user = ?2')
                    ->setParameter(2, $user)
                    ->setParameter(1, $kythue);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukientruythu',
                        'nguoinopthue',
                        'usernnts'
                    ))
                        ->from('Application\Entity\dukientruythu', 'dukientruythu')
                        ->join('dukientruythu.nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join("usernnts.user", "user")
                        ->where('dukientruythu.KyThue = ?1')
                        ->andWhere("user.coquanthue = ?2")
                        ->setParameter(2, $user->getCoquanthue())
                        ->setParameter(1, $kythue);
                }
            
            $this->kq->setKq(true);
            
            $this->kq->setObj($q->getQuery()
                ->getArrayResult());
            $this->kq->setMessenger('Lấy danh sách dự kiến truy thu của kỳ thuế ' . $kythue . ' thành công !');
            return $this->kq->toArray();
        } catch (\Exception $e) {
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }
}
