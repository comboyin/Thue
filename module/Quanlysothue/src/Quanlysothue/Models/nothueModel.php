<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\sono;
use Application\Models\chungtuModel;
use Quanlynguoinopthue\Models\nguoinopthueModel;


class nothueModel extends baseModel
{

    public function dsNoThue($thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'sono',
                    'nguoinopthue',
                    'muclucngansach',
                    'usernnts'
                )
                )
                    ->from('Application\Entity\sono', 'sono')
                    ->join('sono.muclucngansach', 'muclucngansach')
                    ->join('sono.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('sono.KyLapBo = ?1')
                    ->andWhere('usernnts.user = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(2, $user)
                    ->setParameter(1, $thang);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'sono',
                        'nguoinopthue',
                        'muclucngansach',
                        'usernnts'
                    )
                    )
                        ->from('Application\Entity\sono', 'sono')
                        ->join('sono.muclucngansach', 'muclucngansach')
                        ->join('sono.nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('sono.KyLapBo = ?1')
                        ->andWhere('user.parentUser = ?2')
                        ->andWhere('usernnts.ThoiGianKetThuc is null')
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
            
            $this->kq->setMessenger('Lấy danh sách nợ thuế ' . $thang . ' thành công !');
            return $this->kq;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }

    /**
     *
     * @param string $kylapbo
     * @param string $kythue
     * @param string $masothue
     * @param string $tieumuc
     * @return ketqua
     */
    public function findByID_($kylapbo, $kythue, $masothue, $tieumuc)
    {
        /* @var $user user */
        try {
            $kq = new ketqua();
            $obj = $this->em->find('Application\Entity\sono', array(
                'KyLapBo'=>$kylapbo,
                'KyThue'=>$kythue,
                'nguoinopthue'=>$this->em->find('Application\Entity\nguoinopthue', $masothue),
                'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $tieumuc)
            ));
            $kq->setObj($obj);
            $kq->setKq(true);
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    public function dsNoDauKi($thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'sono.KyThue',
                    'nguoinopthue.MaSoThue',
                    'muclucngansach.TieuMuc',
                    'sono.SoTien'
                )
                )
                ->from('Application\Entity\sono', 'sono')
                ->join('sono.muclucngansach', 'muclucngansach')
                ->join('sono.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->where('sono.KyLapBo = ?1')
                ->andWhere('usernnts.user = ?2')
                ->andWhere('usernnts.ThoiGianKetThuc is null')
                ->setParameter(2, $user)
                ->setParameter(1, $thang);
            } else
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'sono.KyThue',
                        'nguoinopthue.MaSoThue',
                        'muclucngansach.TieuMuc',
                        'sono.SoTien'
                    )
                    )
                    ->from('Application\Entity\sono', 'sono')
                    ->join('sono.muclucngansach', 'muclucngansach')
                    ->join('sono.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('sono.KyLapBo = ?1')
                    ->andWhere('user.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
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
    
            $this->kq->setMessenger('Lấy danh sách nợ đầu kì ' . $thang . ' thành công !');
            return $this->kq;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }
    
    public function dsNoPhatSinh($thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'thue.KyThue',
                    'nguoinopthue.MaSoThue',
                    'muclucngansach.TieuMuc',
                    'thue.SoTien'
                )
                )
                ->from('Application\Entity\thue', 'thue')
                ->join('thue.muclucngansach', 'muclucngansach')
                ->join('thue.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->where('thue.KyThue = ?1')
                ->andWhere('usernnts.user = ?2')
                ->andWhere('usernnts.ThoiGianKetThuc is null')
                ->setParameter(2, $user)
                ->setParameter(1, $thang);
            } else
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'thue.KyThue',
                        'nguoinopthue.MaSoThue',
                        'muclucngansach.TieuMuc',
                        'thue.SoTien'
                    )
                    )
                    ->from('Application\Entity\thue', 'thue')
                    ->join('thue.muclucngansach', 'muclucngansach')
                    ->join('thue.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('thue.KyThue = ?1')
                    ->andWhere('user.parentUser = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
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
    
            $this->kq->setMessenger('Lấy danh sách nợ phát sinh trong tháng ' . $thang . ' thành công !');
            return $this->kq;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }
    
    public function ktnothue($kythue, $mst, $tm)
    {
        $q = $this->em->createQueryBuilder();
        $q->select('thue')
        ->from('Application\Entity\thue', 'thue')
        ->join('thue.nguoinopthue', 'nguoinopthue')
        ->join('thue.muclucngansach', 'muclucngansach')
        
        ->where('thue.KyThue = ?1')
        ->andWhere('nguoinopthue.MaSoThue = ?2')
        ->andWhere('muclucngansach.TieuMuc = ?3')
        ->andWhere('thue.SoChungTu is null')
        ->setParameter(3, $tm)
        ->setParameter(2, $mst)
        ->setParameter(1, $kythue);
        
        $kq = $q->getQuery()->getArrayResult();
/*             $this->kq->setKq(true);
            if ($type == 'array') {
                $this->kq->setObj($q->getQuery()
                    ->getArrayResult());
            } else
                if ($type == 'object') {
                    $this->kq->setObj($q->getQuery()
                        ->getResult());
                }
    
            $this->kq->setMessenger('Lấy danh sách nợ phát sinh trong tháng ' . $thang . ' thành công !'); */
        if($kq==null)
            return false; //danophoackcothue
        else
            return true; //nothue
    }
    
    public function LapSoNo($thang, $user)
    {
        $kq = new ketqua();
        $dem = 0;
        try {
            $this->em->getConnection()->beginTransaction();
            
            
            //lay ky lap bo lien truoc
            $Ngay = explode('/', $thang)[0];
            $Nam = explode('/', $thang)[1];
            $KyLapBoTr = "";
            if ($Ngay == "01") {
                $KyLapBoTr = "12" . "/" . ($Nam - 1);
            } else {
                $Ngay = $Ngay - 1;
                if (strlen($Ngay) == 1) {
                    $Ngay = "0" . $Ngay;
                }
                $KyLapBoTr = $Ngay . '/' . $Nam;
            }
            
            //dsnodauki - KyLapBo
            $dsnodauki = $this->dsNoDauKi($KyLapBoTr, $user, 'array')->toArray()['obj'];
            //var_dump($dsnodauki);
        
            //dsnophatsinh - KyThue
            $dsnophatsinh = $this->dsNoPhatSinh($thang, $user, 'array')->toArray()['obj'];
            //var_dump($thang);
            
            //cong 2 mang
            $ds = [];
            foreach ($dsnodauki as $key=>$no)
            {
                array_push($ds, $no);
            }
            foreach ($dsnophatsinh as $key=>$no)
            {
                array_push($ds, $no);
            }
            
            
            if ($ds == null) {
                $this->em->getConnection()->rollBack();
                $kq->setKq(false);
                $kq->setMessenger('<span style="color:red">' . 'Không tìm thấy nợ đầu kì và thuế phát sinh!' . '</span>');
                return $kq;
            }
            
            
            //duyetmang
            foreach ($ds as $key=>$no)
            {
                $KyThue = $no['KyThue'];
                $MaSoThue = $no['MaSoThue'];
                $TieuMuc = $no['TieuMuc'];
                $SoTien = $no['SoTien'];
                
                //kiem tra xem no co dong tien chua? no = true
                if($this->ktnothue($KyThue, $MaSoThue, $TieuMuc)==true)
                {
                   
                    /* @var $nguoinopthue nguoinopthue */
                    /* @var $muclucngansach muclucngansach */
                    $nguoinopthue = $this->em->find('Application\Entity\nguoinopthue', $MaSoThue);
                    $muclucngansach = $this->em->find('Application\Entity\muclucngansach', $TieuMuc);
        
                    $sono = new sono();
                    $sono->setKyLapBo($thang);
                    $sono->setKyThue($KyThue);
                    $sono->setNguoinopthue($nguoinopthue);
                    $sono->setMuclucngansach($muclucngansach);
                    $mo = new chungtuModel($this->em);
                    $SoTien = $mo->getSoTienThue($KyThue, $MaSoThue, $TieuMuc);
                    $sono->setSoTien($SoTien);
                    $this->em->persist($sono);
                    $this->em->flush();
                    $dem ++;
        
                }
        
            }
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green">' . 'Lập sổ nợ của kỳ lập bộ '.$thang.' thành công <br/>
                                Tổng cộng có ' . $dem . ' dòng được lập !' . '</span>');
            
            $this->em->getConnection()->commit();
            $kq->setObj($this->dsNoThue($thang, $user, 'array')
            ->getObj());
            return $kq;
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            $kq->setKq(false);
            $kq->setMessenger('<span style="color:red">' . $e->getMessage() . '</span>');
            return $kq;
        }
    }
    
    
    public function XoaSoNo($thang, $user)
    {
        $kq = new ketqua();
        $dem = 0;
        try {
            $this->em->getConnection()->beginTransaction();
            //dsnodauki - KyLapBo
            $ds = $this->dsNoDauKi($thang, $user, 'array')->toArray()['obj'];
    
            if ($ds == null) {
                $this->em->getConnection()->rollBack();
                $kq->setKq(false);
                $kq->setMessenger('<span style="color:red">' . 'Không tìm thấy nợ thuế trong kỳ lập bộ '.$thang.'!' . '</span>');
                return $kq;
            }
    
    
            //duyetmang
            foreach ($ds as $key=>$no)
            {
                $KyLapBo = $thang;
                $KyThue = $no['KyThue'];
                $MaSoThue = $no['MaSoThue'];
                $TieuMuc = $no['TieuMuc'];
    
                $sono = $this->findByID_($KyLapBo, $KyThue, $MaSoThue, $TieuMuc)->getObj();
                if ($sono == null) {
                    $this->em->getConnection()->rollBack();
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red">' . 'Không tìm thấy nợ thuế '.$KyLapBo." - ".$KyThue." - ".$MaSoThue." - ".$TieuMuc.'</span>');
                    return $kq;
                }
                $kt = new nguoinopthueModel($this->em);
                if($kt->ktNNT($MaSoThue, $user) == true)
                {
                     $this->remove($sono);
                     $dem++;
                }else {
                        $mss = "Người nộp thuế có mã " . $MaSoThue . " không thuộc quyền quản lý của bạn.";
                        $kq->setKq(false);
                        $kq->setMessenger('<span style="color:red">' .$mss.'</span>');
                       $this->em->getConnection()->rollBack();
                        return $kq;
                    }
    
            }
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green">' . 'Xóa sổ nợ của kỳ lập bộ '.$thang.' thành công <br/>
                                Tổng cộng có ' . $dem . ' dòng được xóa !' . '</span>');
    
            $this->em->getConnection()->commit();
            $kq->setObj($this->dsNoThue($thang, $user, 'array')
                ->getObj());
            return $kq;
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            $kq->setKq(false);
            $kq->setMessenger('<span style="color:red">' . $e->getMessage() . '</span>');
            return $kq;
        }
    }
}

?>