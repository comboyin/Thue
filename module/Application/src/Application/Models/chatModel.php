<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;
use Application\Unlity\Unlity;

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
        
            $obj = $this->em->createQueryBuilder()->
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
    
    /**
     * 
     * @param user $user
     * @return \Application\Entity\ketqua  */
    public function DemTamNghi($user)
    {
        $kq = new ketqua();
    
        try {
            $today = (new \DateTime())->format('Y-m-d');
            
            $obj = $this->em->createQueryBuilder()->
            select('COUNT(n)')
            ->from('Application\Entity\nguoinopthue', 'n')
            ->join('n.thongtinngungnghis', 't')
            ->join('n.usernnts','usernnts')
            ->join('usernnts.user', 'user')
            ->where('usernnts.ThoiGianKetThuc is null')
            ->andWhere('user.coquanthue = ?2')
            ->andWhere('t.TuNgay <= ?1')
            ->andWhere('t.DenNgay >= ?1')
            ->setParameter(1,$today)
            ->setParameter(2, $user->getCoquanthue())
            ->getQuery()
            ->getOneOrNullResult();
            if($obj!=NULL){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Đếm thành công !");
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
    
    public function DemChuaDK($user)
    {
        $kq = new ketqua();
    
        try {  
            $DQL = $this->em->createQueryBuilder()
            ->select('n1.MaSoThue')
            ->from('Application\Entity\nguoinopthue', 'n1')
            ->innerJoin('n1.dukienmbs', 'd')
            ->getDQL();
            
            
            $obj = $this->em->createQueryBuilder()->
            select('COUNT(n)')
            ->from('Application\Entity\nguoinopthue', 'n')
            ->join('n.usernnts','usernnts')
            ->join('usernnts.user', 'user')
            ->where('usernnts.ThoiGianKetThuc is null')
            ->andWhere('user.coquanthue = ?1')
            ->andWhere("n.MaSoThue NOT IN ($DQL)")
            ->setParameter(1, $user->getCoquanthue())
            ->getQuery()
            ->getOneOrNullResult();
            if($obj!=NULL){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Đếm thành công !");
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
    
    public function ThueMB($user)
    {
        $kq = new ketqua();
    
        try {    
    
            $obj = $this->em->createQueryBuilder()->
            select('COUNT(n)')
            ->from('Application\Entity\thuemonbai', 'thuemonbai')
            ->join('thuemonbai.nguoinopthue', 'n')
            ->join('n.usernnts','usernnts')
            ->join('usernnts.user', 'user')
            ->where('usernnts.ThoiGianKetThuc is null')
            ->andWhere('user.coquanthue = ?1')
            ->andWhere('thuemonbai.TrangThai = ?2')
            ->setParameter(1, $user->getCoquanthue())
            ->setParameter(2, 0)
            ->getQuery()
            ->getOneOrNullResult();
            if($obj!=NULL){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Đếm thành công !");
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
    
    public function ThueKhoan($user)
    {
        $kq = new ketqua();
    
        try {
    
            $obj = $this->em->createQueryBuilder()->
            select('COUNT(n)')
            ->from('Application\Entity\thue', 'thue')
            ->join('thue.nguoinopthue', 'n')
            ->join('n.usernnts','usernnts')
            ->join('usernnts.user', 'user')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->where('usernnts.ThoiGianKetThuc is null')
            ->andWhere('user.coquanthue = ?1')
            ->andWhere('thue.TrangThai = ?2')
            ->andWhere("muclucngansach.TieuMuc like '1701' OR muclucngansach.TieuMuc like '1003'")
            ->setParameter(1, $user->getCoquanthue())
            ->setParameter(2, 0)
            ->getQuery()
            ->getOneOrNullResult();
            if($obj!=NULL){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Đếm thành công !");
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
    
    public function ThueTTDB($user)
    {
        $kq = new ketqua();
    
        try {
    
            $obj = $this->em->createQueryBuilder()->
            select('COUNT(n)')
            ->from('Application\Entity\thue', 'thue')
            ->join('thue.nguoinopthue', 'n')
            ->join('n.usernnts','usernnts')
            ->join('usernnts.user', 'user')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->where('usernnts.ThoiGianKetThuc is null')
            ->andWhere('user.coquanthue = ?1')
            ->andWhere('thue.TrangThai = ?2')
            ->andWhere("muclucngansach.TieuMuc like '1757'")
            ->setParameter(1, $user->getCoquanthue())
            ->setParameter(2, 0)
            ->getQuery()
            ->getOneOrNullResult();
            if($obj!=NULL){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Đếm thành công !");
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
    
    public function ThueTN($user)
    {
        $kq = new ketqua();
    
        try {
    
            $obj = $this->em->createQueryBuilder()->
            select('COUNT(n)')
            ->from('Application\Entity\thue', 'thue')
            ->join('thue.nguoinopthue', 'n')
            ->join('n.usernnts','usernnts')
            ->join('usernnts.user', 'user')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->where('usernnts.ThoiGianKetThuc is null')
            ->andWhere('user.coquanthue = ?1')
            ->andWhere('thue.TrangThai = ?2')
            ->andWhere("muclucngansach.TieuMuc like '3801'")
            ->setParameter(1, $user->getCoquanthue())
            ->setParameter(2, 0)
            ->getQuery()
            ->getOneOrNullResult();
            if($obj!=NULL){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Đếm thành công !");
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
    
    public function ThueBVMT($user)
    {
        $kq = new ketqua();
    
        try {
    
            $obj = $this->em->createQueryBuilder()->
            select('COUNT(n)')
            ->from('Application\Entity\thue', 'thue')
            ->join('thue.nguoinopthue', 'n')
            ->join('n.usernnts','usernnts')
            ->join('usernnts.user', 'user')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->where('usernnts.ThoiGianKetThuc is null')
            ->andWhere('user.coquanthue = ?1')
            ->andWhere('thue.TrangThai = ?2')
            ->andWhere("muclucngansach.TieuMuc like '2601'")
            ->setParameter(1, $user->getCoquanthue())
            ->setParameter(2, 0)
            ->getQuery()
            ->getOneOrNullResult();
            if($obj!=NULL){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Đếm thành công !");
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
    
    public function DKThue($user)
    {
        $kq = new ketqua();
    
        try {
    
            $obj = $this->em->createQueryBuilder()->
            select('COUNT(n)')
            ->from('Application\Entity\dukienthue', 'dukienthue')
            ->join('dukienthue.nguoinopthue', 'n')
            ->join('n.usernnts','usernnts')
            ->join('usernnts.user', 'user')
            ->where('usernnts.ThoiGianKetThuc is null')
            ->andWhere('user.coquanthue = ?1')
            ->andWhere('dukienthue.TrangThai = ?2')
            ->andWhere("dukienthue.KyThue not like '%/%'")
            ->setParameter(1, $user->getCoquanthue())
            ->setParameter(2, 0)
            ->getQuery()
            ->getOneOrNullResult();
            if($obj!=NULL){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Đếm thành công !");
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

