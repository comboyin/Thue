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
    
    public function duyet($dsMaSoThue,$dsTieuMuc,$KyThue){
        
        $kq= new ketqua();
        $demDuKienDuyet =0;
        
        try {
            $this->em->getConnection()->beginTransaction();
            foreach ($dsMaSoThue as $key=>$value){
                $MaSoThue = $value;
                $TieuMuc = $dsTieuMuc[$key];
                /* @var $duKienNam dukienthue */
                $duKienNam = $this->em->find('Application\Entity\dukienthue', array(
                    'nguoinopthue'=>$this->em->find('Application\Entity\nguoinopthue', $MaSoThue),
                    'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $TieuMuc),
                    'KyThue' => $KyThue
                ));

                // khong tim thay
                if($duKienNam==null){
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red;" >'."Không tìm thấy dự kiến năm ".$MaSoThue.'-'.$TieuMuc."-".$KyThue.'<br/></span>');
                    
                    $this->em->getConnection()->rollBack();
                    return $kq;
                }
                
                // du kien nay da duyet
                if($duKienNam->getTrangThai()==1){
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red;" >'."Dự kiến thuế năm ".$MaSoThue.'-'.$TieuMuc."-".$KyThue . " Đã được duyệt ! Vui lòng kiểm tra và thử lại !".'<br/></span>');
                    
                    $this->em->getConnection()->rollBack();
                    return $kq;
                }
                
                $duKienNam->setTrangThai(1);
                $this->em->flush();
                $demDuKienDuyet++;
                
            }
            
            

            $this->em->getConnection()->commit();
            
            
            //thanh cong - thong bao bao nhieu du kien da dc duyet
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green;" >Tất cả dự kiến thuế được chọn đã được duyệt hoàn tất<br/>
                                Tổng số '.$demDuKienDuyet . 'Dự kiến đã được duyệt </span>');
            
            return $kq;
            
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger('<span style="color:red;" >'.$e->getMessage().'<br/></span>');
            $this->em->getConnection()->rollBack();
            return $kq;
            
        }
    }

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
                    'muclucngansach',
                    'usernnts',
                    'user'
                ))
                ->from('Application\Entity\dukienthue', 'dukienthue')
                ->join('dukienthue.user', 'user')
                ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                ->join('dukienthue.muclucngansach', 'muclucngansach')
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
                        'muclucngansach',
                        'usernnts',
                        'user'
                    ))
                    ->from('Application\Entity\dukienthue', 'dukienthue')
                    ->join('dukienthue.user', 'user')
                    ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                    ->join('dukienthue.muclucngansach', 'muclucngansach')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user1')
                    ->where('dukienthue.KyThue = ?1')
                    ->andWhere('user1.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(2, $user)
                    ->setParameter(1, $nam);
                }
            
            $this->kq->setKq(true);
            $this->kq->setObj($q->getQuery()
                ->getArrayResult());
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
