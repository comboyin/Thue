<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\truythu;
use Application\Entity\dukientruythu;
use Application\Unlity\Unlity;

class thuetruythuModel extends baseModel
{

    public function dsThueTruyThu($thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'truythu',
                    'nguoinopthue',
                    'usernnts'
                )
                )
                    ->from('Application\Entity\truythu', 'truythu')
                    ->join('truythu.nguoinopthue', 'nguoinopthue')
                    ->join('truythu.muclucngansach', 'muclucngansach')
                    ->
                join('nguoinopthue.usernnts', 'usernnts')
                    ->
                where('truythu.KyThue = ?1')
                    ->andWhere('usernnts.user = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->andWhere("muclucngansach.TieuMuc like '1003' or muclucngansach.TieuMuc like '1701'")
                    ->setParameter(2, $user)
                    ->setParameter(1, $thang);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'truythu',
                        'nguoinopthue',
                        'usernnts'
                    )
                    )
                        ->from('Application\Entity\truythu', 'truythu')
                        ->join('truythu.muclucngansach', 'muclucngansach')
                        ->join('truythu.nguoinopthue', 'nguoinopthue')
                        ->
                    join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('truythu.KyThue = ?1')
                        ->andWhere('user.parentUser = ?2')
                        ->andWhere('usernnts.ThoiGianKetThuc is null')
                        ->andWhere("muclucngansach.TieuMuc like '1003' or muclucngansach.TieuMuc like '1701'")
                        ->setParameter(2, $user)
                        ->setParameter(1, $thang);
                }
            $this->kq->setKq(true);
            if ($type == 'array') {
                $this->kq->setObj($q->getQuery()
                    ->getArrayResult());
            } else 
                if ($type == 'object') {
                    $this->kq->setObj($q->getQuery()
                        ->getResult());
                }
            
            $this->kq->setMessenger('Lấy danh sách dự truy thu truy thu ' . $thang . ' thành công !');
            return $this->kq;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }

    public function ghiso($dsMaSoThue, $dsTieuMuc, $Thang)
    {
        $kq = new ketqua();
        $dem = 0;
        
        try {
            $this->em->getConnection()->beginTransaction();
            foreach ($dsMaSoThue as $key => $value) {
                // tim du kien truy thu
                $dukientruythu = $this->em->find('Application\Entity\dukientruythu', array(
                    'nguoinopthue' => $this->em->find('Application\Entity\nguoinopthue', $value),
                    'muclucngansach' => $this->em->find('Application\Entity\muclucngansach', $dsTieuMuc[$key]),
                    'KyThue' => $Thang
                ));
                if ($dukientruythu == null) {
                    $this->em->getConnection()->rollBack();
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red">' . "Không tìm thấy dự kiến truy thu " . $value . " - " . $dsTieuMuc[$key] . " - " . $Thang . '</span>');
                    return $kq;
                }
                /* @var $dukientruythu dukientruythu */
                $thuetruythu = new truythu();
                $thuetruythu->setNguoinopthue($this->em->find('Application\Entity\nguoinopthue', $value));
                $thuetruythu->setMuclucngansach($this->em->find('Application\Entity\muclucngansach', $dsTieuMuc[$key]));
                $thuetruythu->setKyThue($Thang);
                
                $thuetruythu->setDoanhSo($dukientruythu->getDoanhSo());
                $thuetruythu->setTiLeTinhThue($dukientruythu->getTiLeTinhThue());
                $thuetruythu->setLyDo($dukientruythu->getLyDo());
                $thuetruythu->setSoTien($dukientruythu->getSoTien());
                
                //$thuetruythu->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $dukientruythu->getNgayPhaiNop(), 'Y-m-d')); // 2015-07-28
                $thuetruythu->setTrangThai(0);
                

                $this->em->persist($thuetruythu);
                //$this->em->merge($dukientruythu);
                $this->em->flush();
                $dem ++;
            }
            
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green">' . 'Tẩt cả dự kiến truy thu ' . $Thang . ' được chọn đã ghi sổ thành công <br/>
                                Tổng cộng có ' . $dem . ' dự kiến truy thu được ghi sổ !' . '</span>');
            
            $this->em->getConnection()->commit();
            
            return $kq;
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            $kq->setKq(false);
            $kq->setMessenger('<span style="color:red">' . $e->getMessage() . '</span>');
            return $kq;
        }
    }
    public function duyet($dsMaSoThue,$dsTieuMuc,$Thang){
        $kq= new ketqua();
        $dem =0;
        
        try {
            $this->em->getConnection()->beginTransaction();
            foreach ($dsMaSoThue as $key=>$value){
                $MaSoThue = $value;
                $TieuMuc = $dsTieuMuc[$key];
                
                /* @var $truythu truythu */
                $truythu = $this->em->find('Application\Entity\truythu', array(
                    'nguoinopthue'=>$this->em->find('Application\Entity\nguoinopthue', $MaSoThue),
                    'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $TieuMuc),
                    'KyThue' => $Thang
                ));
        
                // khong tim thay
                if($truythu==null){
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red;" >'."Không tìm thấy ".$MaSoThue.'-'.$TieuMuc."-".$Thang.'<br/></span>');
        
                    $this->em->getConnection()->rollBack();
                    return $kq;
                }
        
                //truythu da duoc duyet
                if($truythu->getTrangThai()==1){
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red;" >'."Dự kiến truy thu ".$MaSoThue.'-'.$TieuMuc."-".$Thang . " Đã được duyệt ! Vui lòng kiểm tra và thử lại !".'<br/></span>');
        
                    $this->em->getConnection()->rollBack();
                    return $kq;
                }
        
                $truythu->setTrangThai(1);
                $this->em->flush();
                $dem++;
        
            }
        
        
        
            $this->em->getConnection()->commit();
        
        
            //thanh cong - thong bao bao nhieu du kien da dc duyet
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green;" >Tất cả truy thu được chọn đã được duyệt hoàn tất<br/>
                                Tổng số '.$dem . 'truy thu đã được duyệt </span>');
        
            return $kq;
        
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger('<span style="color:red;" >'.$e->getMessage().'<br/></span>');
            $this->em->getConnection()->rollBack();
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
    public function findByID_($kythue, $masothue, $tieumuc)
    {
        /* @var $user user */
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
    
            $qb->select('truythu')
            ->from('Application\Entity\truythu', 'truythu')
            ->join('truythu.nguoinopthue', 'nguoinopthue')
            ->join('truythu.muclucngansach', 'muclucngansach')
            ->where('nguoinopthue.MaSoThue = ?1')
            ->andWhere('muclucngansach.TieuMuc = ?2')
            ->andWhere('truythu.KyThue = ?3')
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
}

?>