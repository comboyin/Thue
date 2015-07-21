<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\thue;
use Application\Entity\dukienthue;
use Application\Unlity\Unlity;

class thuetainguyenModel extends baseModel
{

    public function dsThueTaiNguyen($thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'thue',
                    'nguoinopthue',
                    'usernnts'
                )
                )
                    ->from('Application\Entity\thue', 'thue')
                    ->join('thue.muclucngansach', 'muclucngansach')
                    ->join('thue.nguoinopthue', 'nguoinopthue')
                    ->
                join('nguoinopthue.usernnts', 'usernnts')
                    ->
                where('thue.KyThue = ?1')
                    ->andWhere('usernnts.user = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->andWhere("muclucngansach.TieuMuc like '3801'")
                    ->setParameter(2, $user)
                    ->setParameter(1, $thang);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'thue',
                        'nguoinopthue',
                        'usernnts'
                    )
                    )
                        ->from('Application\Entity\thue', 'thue')
                        ->join('thue.muclucngansach', 'muclucngansach')
                        ->join('thue.nguoinopthue', 'nguoinopthue')
                        ->
                    join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('thue.KyThue = ?1')
                        ->andWhere('user.parentUser = ?2')
                        ->andWhere('usernnts.ThoiGianKetThuc is null')
                        ->andWhere("muclucngansach.TieuMuc like '3801'")
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
            
            $this->kq->setMessenger('Lấy danh sách thuế tài nguyên ' . $thang . ' thành công !');
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
                // tim du kien thang
                $dukienthuethang = $this->em->find('Application\Entity\dukienthue', array(
                    'nguoinopthue' => $this->em->find('Application\Entity\nguoinopthue', $value),
                    'muclucngansach' => $this->em->find('Application\Entity\muclucngansach', $dsTieuMuc[$key]),
                    'KyThue' => $Thang
                ));
                if ($dukienthuethang == null) {
                    $this->em->getConnection()->rollBack();
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red">' . "Không tìm thấy dự kiến thuế " . $value . " - " . $dsTieuMuc[$key] . " - " . $Thang . '</span>');
                    return $kq;
                }
                /* @var $dukienthuethang dukienthue */
                $thuetainguyen = new thue();
                $thuetainguyen->setNguoinopthue($this->em->find('Application\Entity\nguoinopthue', $value));
                $thuetainguyen->setMuclucngansach($this->em->find('Application\Entity\muclucngansach', $dsTieuMuc[$key]));
                $thuetainguyen->setKyThue($Thang);
                $thuetainguyen->setTenGoi($dukienthuethang->getTenGoi());
                $thuetainguyen->setSanLuong($dukienthuethang->getSanLuong());
                
                $thuetainguyen->setDoanhThuChiuThue($dukienthuethang->getDoanhThuChiuThue());
                
                $thuetainguyen->setGiaTinhThue($dukienthuethang->getGiaTinhThue());
                
                $thuetainguyen->setTiLeTinhThue($dukienthuethang->getTiLeTinhThue());
                
                $thuetainguyen->setThueSuat($dukienthuethang->getThueSuat());
                
                $thuetainguyen->setSoTien($dukienthuethang->getSoTien());
                
                $thuetainguyen->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $dukienthuethang->getNgayPhaiNop(), 'Y-m-d')); // 2015-07-28
                $thuetainguyen->setTrangThai(0);
                
                //set trạng thái cho dự kiến tháng - đã ghi
                //$dukienthuethang->setTrangThai(1);
                $this->em->persist($thuetainguyen);
                $this->em->merge($dukienthuethang);
                $this->em->flush();
                $dem ++;
            }
            
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green">' . 'Tẩt cả dự kiến tài nguyên ' . $Thang . ' được chọn đã ghi sổ thành công <br/>
                                Tổng cộng có ' . $dem . ' dự kiến tài nguyên được ghi sổ !' . '</span>');
            
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
                
                /* @var $thue thue */
                $thue = $this->em->find('Application\Entity\thue', array(
                    'nguoinopthue'=>$this->em->find('Application\Entity\nguoinopthue', $MaSoThue),
                    'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $TieuMuc),
                    'KyThue' => $Thang
                ));
        
                // khong tim thay
                if($thue==null){
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red;" >'."Không tìm thấy ".$MaSoThue.'-'.$TieuMuc."-".$Thang.'<br/></span>');
        
                    $this->em->getConnection()->rollBack();
                    return $kq;
                }
        
                //thue da duoc duyet
                if($thue->getTrangThai()==1){
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red;" >'."Thuế tài nguyên ".$MaSoThue.'-'.$TieuMuc."-".$Thang . " Đã được duyệt ! Vui lòng kiểm tra và thử lại !".'<br/></span>');
        
                    $this->em->getConnection()->rollBack();
                    return $kq;
                }
        
                $thue->setTrangThai(1);
                $this->em->flush();
                $dem++;
        
            }
        
        
        
            $this->em->getConnection()->commit();
        
        
            //thanh cong - thong bao bao nhieu du kien da dc duyet
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green;" >Tất cả thuế tài nguyên được chọn đã được duyệt hoàn tất<br/>
                                Tổng số '.$dem . 'thuế tài nguyên đã được duyệt </span>');
        
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
    public function findByID_($kythue, $masothue,$tieumuc)
    {
        /* @var $user user */
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
    
            $qb->select('thue')
            ->from('Application\Entity\thue', 'thue')
            ->join('thue.nguoinopthue', 'nguoinopthue')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->where('nguoinopthue.MaSoThue = ?1')
            ->andWhere('muclucngansach.TieuMuc = ?2')
            ->andWhere('thue.KyThue = ?3')
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