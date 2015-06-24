<?php
namespace Quanlynguoinopthue\Models;

use Application\base\baseModel;
use Application\Entity\user;
use Application\Entity\ketqua;

class nguoinopthueModel extends baseModel
{

    public function findById($id)
    {
        // TODO Auto-generated method stub
    }

    public function findByIdToArray($masothue, $user)
    {
        // cbt
        $kq = new ketqua();
        try {
            if ($user->getLoaiUser() == 4) {
                $ma = $user->getMaUser();
                
                $query = $this->em->createQueryBuilder()
                    ->select(array(
                    'n'
                ))
                    ->from('Application\Entity\nguoinopthue', 'n')
                    ->join('n.usernnts', 'k')
                    ->join('k.user', 'u')
                    ->where('u.MaUser = ?1')
                    ->andWhere('n.MaSoThue = ?2')
                    ->setParameter(2, $masothue)
                    ->setParameter(1, $ma);
                $kq->setObj($query->getQuery()
                    ->getArrayResult());
                // var_dump($kq->getObj());
                $kq->setKq(true);
                
                return $kq;
            }
        } catch (\Exception $e) {
            
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    public function getDanhSach()
    {
        // TODO Auto-generated method stub
    }

    public function sua($id)
    {
        // TODO Auto-generated method stub
    }



    public function xoa($id)
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * @param user $user            
     * @return \Application\Entity\ketqua
     */
    public function DanhSachByIdentity($user)
    {
        // cbt
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        $ma = $user->getMaUser();
        
        try {
            if ($user->getLoaiUser() == 4) {
                
                $qb->select(array(
                    'nguoinopthue',
                    'usernnts',
                    'user',
                    'NNTNganhs',
                    'nganh',
                    'thongtinnnt',
                    'phuong',
                    'coquanthue'
                    
                    
                    
                    
                ))
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->join('nguoinopthue.NNTNganhs', 'NNTNganhs')
                    ->join('NNTNganhs.nganh', 'nganh')
                    ->join('nguoinopthue.thongtinnnt', 'thongtinnnt')
                    ->join('thongtinnnt.phuong', 'phuong')
                    ->join('phuong.coquanthue', 'coquanthue')
                    ->where('user.MaUser = ?1')
                    ->andwhere('usernnts.ThoiGianKetThuc is null')
                    ->andwhere('NNTNganhs.ThoiGianKetThuc is null')
                    ->andwhere('thongtinnnt.ThoiGianKetThuc is null')
                    ->
                // ->andWhere('nguoinopthueot in('.$qb->getDQL().')')
                setParameter(1, $ma);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $qb->select(array(
                        'nguoinopthue',
                        'usernnts',
                        'user',
                        'NNTNganhs',
                        'nganh',
                        'thongtinnnt',
                        'phuong',
                        'coquanthue'
                    ))
                        ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->join('nguoinopthue.NNTNganhs', 'NNTNganhs')
                        ->join('NNTNganhs.nganh', 'nganh')
                        ->join('nguoinopthue.thongtinnnt', 'thongtinnnt')
                        ->join('thongtinnnt.phuong', 'phuong')
                        ->join('phuong.coquanthue', 'coquanthue')
                        ->where('coquanthue = ?1')
                        ->andwhere('usernnts.ThoiGianKetThuc is null')
                        ->andwhere('NNTNganhs.ThoiGianKetThuc is null')
                        ->andwhere('thongtinnnt.ThoiGianKetThuc is null')
                        ->setParameter(1, $user->getCoquanthue());
                }
            
            $obj = $qb->getQuery()->getResult();
            
            $kq->setObj($obj);
            $kq->setKq(true);
            $kq->setMessenger("Lấy danh sách người nộp thuế thành công");
            
            return $kq;
        } catch (\Exception $e) {
            var_dump($e);
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     * Trả về danh sách người nộp thuế chưa có dự kiến thuế trong [kỳ thuế] đó
     *
     * @param string $kythue            
     * @param user $user            
     * @return ketqua
     */
    public function dsNNTChuaCoDKDSTrongKyThue($kythue, $user)
    {
        try {
            
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
            $qb1 = $this->em->createQueryBuilder();
            if ($user->getLoaiUser() == 4) {
                
                // DQL : Lay nguoinopthue có dự kiến thuế trong kỳ thuế $kythue và thuôc user
                $qb1->select('nguoinopthue1')
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue1')
                    ->join('nguoinopthue1.dukientruythus', 'dukientruythus')
                    ->where("dukientruythus.user = :user")
                    ->andWhere("dukientruythus.KyThue = :kythue");
                // DQL: Lay nguoinopthue chưa có dự kiến thuế trong kỳ thuế $kythue và thuôc user
                $qb->select(array(
                    'nguoinopthue'
                ))
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where($qb->expr()
                    ->notIn('nguoinopthue', $qb1->getDQL()))
                    ->andWhere('usernnts.user = :user')
                    ->groupBy('nguoinopthue.MaSoThue')
                    ->setParameter(':user', $user)
                    ->setParameter(':kythue', $kythue);
                
                $kq->setObj($qb->getQuery()
                    ->getArrayResult());
                if ($kq->getObj() != null) {
                    $kq->setKq(true);
                    $kq->setMessenger('Lấy danh sách thành công !');
                    return $kq;
                } else {
                    $kq->setKq(false);
                    $kq->setMessenger('Không thể trả về kết quả như mong muốn !');
                    return $kq;
                }
            }
            
            return $kq;
        } catch (\Exception $e) {
            
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     * Danh sách người nôp thuế chưa có dự kiến thuế của loại thuế(tiểu mục)
     * trong 1 kỳ thuế của user
     *
     * @param string $KyThue            
     * @param string $TieuMuc            
     * @param user $User            
     */
    public function dsNNTKhongCoDuKienThue($KyThue, $TieuMuc, $User)
    {
        try {
            
            if ($User->getLoaiUser() == 4) {
                
                $qbCoDuKien = $this->em->createQueryBuilder();
                // lay danh sach nnt có du kien thue
                $qbCoDuKien->select('nguoinopthue1')
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue1')
                    ->join('nguoinopthue1.dukienthues', 'dukienthues')
                    ->join('dukienthues.muclucngansach', 'muclucngansach')
                    ->where("dukienthues.user = :user")
                    ->andWhere("muclucngansach.TieuMuc = :tieumuc")
                    ->andWhere("dukienthues.KyThue = :kythue");
                
                // DQL: Lay nguoinopthue chưa có dự kiến thuế trong kỳ thuế $kythue và thuôc user
                $qbKhongCoDuKien = $this->em->createQueryBuilder();
                
                $qbKhongCoDuKien->select(array(
                    'nguoinopthue'
                ))
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where($qbKhongCoDuKien->expr()
                    ->notIn('nguoinopthue', $qbCoDuKien->getDQL()))
                    ->andWhere('usernnts.user = :user')
                    ->setParameter(':user', $User)
                    ->setParameter(':tieumuc', $TieuMuc)
                    ->setParameter(':kythue', $KyThue)
                    ->groupBy('nguoinopthue.MaSoThue');
                
                $this->kq->setObj($qbKhongCoDuKien->getQuery()
                    ->getArrayResult());
                
                if ($this->kq->getObj() != null) {
                    $this->kq->setKq(true);
                    $this->kq->setMessenger('Lấy danh sách thành công !');
                    return $this->kq;
                } else {
                    $this->kq->setKq(false);
                    $this->kq->setMessenger('Không thể trả về kết quả như mong muốn !');
                    return $this->kq;
                }
            }
        } catch (\Exception $e) {
            var_dump($e);
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     *
     * @param user $user            
     * @return
     */
    public function dsNNTbyUser($user)
    {
        $bq = $this->em->createQueryBuilder();
        if ($user->getLoaiUser() == 4) {
            $bq->select('nguoinopthue')
                ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->where("usernnts.user = ?1")
                ->andwhere("usernnts.ThoiGianKetThuc is null")
                ->setParameter(1, $user);
            return json_encode($bq->getQuery()->getArrayResult());
        } else 
            if ($user->getLoaiUser() == 3) {
                $bq->select('nguoinopthue')
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where("user.coquanthue = ?1")
                    ->andwhere("usernnts.ThoiGianKetThuc is null")
                    ->setParameter(1, $user->getCoquanthue());
                return json_encode($bq->getQuery()->getArrayResult());
            }
    }
}

?>