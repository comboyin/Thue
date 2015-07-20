<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\dukienthue;

class dukienthuecuathangModel extends baseModel
{

    public function dsDKthuecuanamDaDuyet($nam, $thang, $user)
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
                    ->join('dukienthue.muclucngansach', 'muclucngansach')
                    ->join('dukienthue.user', 'user')
                    ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('dukienthue.KyThue = ?1')
                    ->andWhere('dukienthue.TrangThai = ?3')
                    ->andWhere('usernnts.user = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->andWhere('not exists (select dukienthue1 from Application\Entity\dukienthue dukienthue1 where dukienthue1.nguoinopthue
                            = nguoinopthue and dukienthue1.KyThue = ?4 and dukienthue1.muclucngansach = muclucngansach)')
                    ->setParameter(3, 1)
                    ->setParameter(2, $user)
                    ->setParameter(1, $nam)
                    ->setParameter(4, $thang);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukienthue',
                        'nguoinopthue',
                        'usernnts',
                        'user1'
                    ))
                        ->from('Application\Entity\dukienthue', 'dukienthue')
                        ->join('dukienthue.muclucngansach', 'muclucngansach')
                        ->join('dukienthue.user', 'user1')
                        ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('dukienthue.KyThue = ?1')
                        ->andWhere('dukienthue.TrangThai = ?3')
                        ->andWhere('user.parentUser = ?2')
                        ->andWhere('usernnts.ThoiGianKetThuc is null')
                        ->andWhere('not exists (select dukienthue1 from Application\Entity\dukienthue dukienthue1 where dukienthue1.nguoinopthue
                            = nguoinopthue and dukienthue1.KyThue = ?4 and dukienthue1.muclucngansach = muclucngansach)')
                        ->setParameter(3, 1)
                        ->setParameter(2, $user)
                        ->setParameter(1, $nam)
                        ->setParameter(4, $thang);
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

    public function SangSo($dsMaSoThue, $dsTieuMuc, $Nam, $Thang, $user)
    {
        $kq = new ketqua();
        $dem = 0;
        
        try {
            $this->em->getConnection()->beginTransaction();
            foreach ($dsMaSoThue as $key => $value) {
                // tim du kien nam
                $dukienthuenam = $this->em->find('Application\Entity\dukienthue', array(
                    'nguoinopthue' => $this->em->find('Application\Entity\nguoinopthue', $value),
                    'muclucngansach' => $this->em->find('Application\Entity\muclucngansach', $dsTieuMuc[$key]),
                    'KyThue' => $Nam
                ));
                if ($dukienthuenam == null) {
                    $this->em->getConnection()->rollBack();
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red">' . "Không tìm thấy dự kiến thuế " . $value . " - " . $dsTieuMuc[$key] . " - " . $Nam . '</span>');
                    return $kq;
                }
                /* @var $dukienthuenam dukienthue */
                $dukienthuethang = new dukienthue();
                $dukienthuethang->setNguoinopthue($this->em->find('Application\Entity\nguoinopthue', $value));
                $dukienthuethang->setMuclucngansach($this->em->find('Application\Entity\muclucngansach', $dsTieuMuc[$key]));
                $dukienthuethang->setKyThue($Thang);
                $dukienthuethang->setTenGoi($dukienthuenam->getTenGoi());
                $dukienthuethang->setSanLuong($dukienthuenam->getSanLuong());
                
                $dukienthuethang->setDoanhThuChiuThue($dukienthuenam->getDoanhThuChiuThue());
                
                $dukienthuethang->setGiaTinhThue($dukienthuenam->getGiaTinhThue());
                
                $dukienthuethang->setTiLeTinhThue($dukienthuenam->getTiLeTinhThue());
                
                $dukienthuethang->setThueSuat($dukienthuenam->getThueSuat());
                
                $dukienthuethang->setSoTien($dukienthuenam->getSoTien());
                
                $temp = explode('/', $Thang);
                $dukienthuethang->setNgayPhaiNop($temp[1] . "-" . $temp[0] . "-" . '28'); // 2015-07-28
                $dukienthuethang->setTrangThai(0);
                $dukienthuethang->setUser($user);
                
                $this->em->persist($dukienthuethang);
                $this->em->flush();
                $dem ++;
            }
            
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green">' . 'Tẩt cả dự kiến được chọn đã sang sổ thành công <br/>
                                Tổng cộng có ' . $dem . ' dự kiến năm được sang !' . '</span>');
            
            $this->em->getConnection()->commit();
            
            return $kq;
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            $kq->setKq(false);
            $kq->setMessenger('<span style="color:red">' . $e->getMessage() . '</span>');
            return $kq;
        }
    }

    public function DSDKThueKhoan($Thang, $user, $type)
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
                    ->join('dukienthue.muclucngansach', 'muclucngansach')
                    ->join('dukienthue.user', 'user')
                    ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('dukienthue.KyThue = ?1')
                    ->andWhere('dukienthue.TrangThai = ?3')
                    ->andWhere('usernnts.user = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(1, $Thang)
                    ->setParameter(3, 0)
                    ->setParameter(2, $user);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'dukienthue',
                        'nguoinopthue',
                        'usernnts',
                        'user1'
                    ))
                        ->from('Application\Entity\dukienthue', 'dukienthue')
                        ->join('dukienthue.muclucngansach', 'muclucngansach')
                        ->join('dukienthue.user', 'user1')
                        ->join('dukienthue.nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('dukienthue.KyThue = ?1')
                        ->andWhere('dukienthue.TrangThai = ?3')
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

?>