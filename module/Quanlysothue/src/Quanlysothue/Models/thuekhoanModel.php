<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\thue;
use Application\Entity\dukienthue;
use Application\Unlity\Unlity;

class thuekhoanModel extends baseModel
{

    public function dsThueKhoan($thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'thue',
                    'nguoinopthue',
                    'muclucngansach',
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
                    ->andWhere("muclucngansach.TieuMuc like '1003' or muclucngansach.TieuMuc like '1701'")
                    ->setParameter(2, $user)
                    ->setParameter(1, $thang);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'thue',
                        'nguoinopthue',
                        'muclucngansach',
                        'usernnts'
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
            
            $this->kq->setMessenger('Lấy danh sách thuế khoán ' . $thang . ' thành công !');
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
                // tim du kien nam
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
                $thuekhoan = new thue();
                $thuekhoan->setNguoinopthue($this->em->find('Application\Entity\nguoinopthue', $value));
                $thuekhoan->setMuclucngansach($this->em->find('Application\Entity\muclucngansach', $dsTieuMuc[$key]));
                $thuekhoan->setKyThue($Thang);
                $thuekhoan->setTenGoi($dukienthuethang->getTenGoi());
                $thuekhoan->setSanLuong($dukienthuethang->getSanLuong());
                
                $thuekhoan->setDoanhThuChiuThue($dukienthuethang->getDoanhThuChiuThue());
                
                $thuekhoan->setGiaTinhThue($dukienthuethang->getGiaTinhThue());
                
                $thuekhoan->setTiLeTinhThue($dukienthuethang->getTiLeTinhThue());
                
                $thuekhoan->setThueSuat($dukienthuethang->getThueSuat());
                
                $thuekhoan->setSoTien($dukienthuethang->getSoTien());
                
                $thuekhoan->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $dukienthuethang->getNgayPhaiNop(), 'Y-m-d')); // 2015-07-28
                $thuekhoan->setTrangThai(0);
                
                //set trạng thái cho dự kiến tháng - đã ghi
                //$dukienthuethang->setTrangThai(1);
                $this->em->persist($thuekhoan);
                $this->em->merge($dukienthuethang);
                $this->em->flush();
                $dem ++;
            }
            
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green">' . 'Tẩt cả dự kiến thuế khoán ' . $Thang . ' được chọn đã ghi sổ thành công <br/>
                                Tổng cộng có ' . $dem . ' dự kiến thuế khoán được ghi sổ !' . '</span>');
            
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
                    $kq->setMessenger('<span style="color:red;" >'."Thuế khoán".$MaSoThue.'-'.$TieuMuc."-".$Thang . " Đã được duyệt ! Vui lòng kiểm tra và thử lại !".'<br/></span>');
        
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
            $kq->setMessenger('<span style="color:green;" >Tất cả thuế được chọn đã được duyệt hoàn tất<br/>
                                Tổng số '.$dem . 'thuế đã được duyệt </span>');
        
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
            $obj = $this->em->find('Application\Entity\thue', array(
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
}

?>