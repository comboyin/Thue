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
                    'dukienthue',
                    'nguoinopthue',
                    'muclucngansach',
                    'usernnts',
                    'user1'
                ))
                    ->from('Application\Entity\dukientruythu', 'dukientruythu')
                    ->join('dukientruythu.user','user1')
                    ->join('dukientruythu.dukienthue', 'dukienthue')
                    ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                    ->join('dukienthue.muclucngansach', 'muclucngansach')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('dukienthue.KyThue = ?1')
                    ->andWhere('user1 = ?2')
                    ->setParameter(2, $user)
                    ->setParameter(1, $kythue);
            } else 
                if ($user->getLoaiUser() == 3) {
                     $q->select(array(
                        'dukientruythu',
                        'dukienthue',
                        'nguoinopthue',
                        'muclucngansach',
                        'usernnts',
                        'user1'
                    ))
                        ->from('Application\Entity\dukientruythu', 'dukientruythu')
                        ->join('dukientruythu.user','user1')
                        ->join('dukientruythu.dukienthue', 'dukienthue')
                        ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                        ->join('dukienthue.muclucngansach', 'muclucngansach')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join("usernnts.user", "user")
                        ->where('dukienthue.KyThue = ?1')
                        ->andWhere("user1.parentUser = ?2")
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
                ->join('dukientruythu.dukienthue', 'dukienthue')
                ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                ->join('dukienthue.muclucngansach', 'muclucngansach')
                ->where('nguoinopthue.MaSoThue = ?1')
                ->andWhere('muclucngansach.TieuMuc = ?2')
                ->andWhere('dukienthue.KyThue = ?3')
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
     * Danh sách dự kiến truy thu theo kỳ thuế và của user
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
                /*
                 * Danh sach du kien truy thu cua Can Bo Vien cua DOI Truong do.
                 *   */
                $q->select(array(
                    'dukientruythu',
                    'dukienthue',
                    'nguoinopthue',
                    'muclucngansach',
                    'usernnts',
                    'user1'
                ))
                    ->from('Application\Entity\dukientruythu', 'dukientruythu')
                    ->join('dukientruythu.dukienthue', 'dukienthue')
                    ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                    ->join('dukienthue.muclucngansach', 'muclucngansach')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->join('dukientruythu.user', 'user1')
                    ->where('dukienthue.KyThue = ?1')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->andWhere('user = ?2')
                    ->setParameter(2, $user)
                    ->setParameter(1, $kythue);
            } else 
                if ($user->getLoaiUser() == 3) {
                    
                    /*
                     * Danh sach du kien truy thu cua Doi Truong va Can Bo Vien cua DOI Truong do.
                     *   */
                    
                    $q->select(array(
                        'dukientruythu',
                        'dukienthue',
                        'nguoinopthue',
                        'muclucngansach',
                        'usernnts',
                        'user1'
                    ))
                        ->from('Application\Entity\dukientruythu', 'dukientruythu')
                        
                        ->join('dukientruythu.dukienthue', 'dukienthue')
                        ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                        ->join('dukienthue.muclucngansach', 'muclucngansach')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join("usernnts.user", "user")
                        ->join('dukientruythu.user','user1')
                        ->where('dukienthue.KyThue = ?1')
                        ->andWhere('usernnts.ThoiGianKetThuc is null')
                        ->andWhere("user.parentUser = ?2")
                        ->setParameter(2, $user)
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
    
    public function DSDKThueTruyThu($Thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'dukientruythu',
                    'nguoinopthue',
                    'usernnts',
                    'user'
                ))
                ->from('Application\Entity\dukientruythu', 'dukientruythu')
                ->join('dukientruythu.muclucngansach', 'muclucngansach')
                ->join('dukientruythu.user', 'user')
                ->join('dukientruythu.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->where('dukientruythu.KyThue = ?1')
                ->andWhere('dukientruythu.TrangThai = ?3')
                ->andWhere('usernnts.user = ?2')
                ->andWhere('usernnts.ThoiGianKetThuc is null')
                ->setParameter(1, $Thang)
                ->setParameter(3, 0)
                ->setParameter(2, $user);
            } else
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukientruythu',
                        'nguoinopthue',
                        'usernnts',
                        'user1'
                    ))
                    ->from('Application\Entity\dukientruythu', 'dukientruythu')
                    ->join('dukientruythu.muclucngansach', 'muclucngansach')
                    ->join('dukientruythu.user', 'user1')
                    ->join('dukientruythu.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('dukientruythu.KyThue = ?1')
                    ->andWhere('dukientruythu.TrangThai = ?3')
                    ->andWhere('user.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(1, $Thang)
                    ->setParameter(3, 0)
                    ->setParameter(2, $user);
                }
            $this->kq->setKq(true);
            if($type=='array'){
                $this->kq->setObj($q->getQuery()
                    ->getArrayResult());
            }
            else if($type=='object'){
                $this->kq->setObj($q->getQuery()
                    ->getResult());
            }
    
    
            $this->kq->setMessenger('Lấy danh sách dự thuế của tháng ' . $Thang . ' thành công !');
            return $this->kq->toArray();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }
}
