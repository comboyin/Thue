<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;
use Application\Entity\chungtu;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\nguoinopthue;
use Application\Entity\thuemonbai;

class chungtuModel extends baseModel
{

    /**
     * Ajax
     * param : Y-m-d
     * trả về danh sách (Array) chứng từ giữa 2 thời gian truyền vào
     *
     * @param string $startDate            
     * @param string $endDate            
     * @param user $user            
     * @param string $type            
     * @return \Application\Models\ketqua
     */
    public function DanhSachChungTuGiuaNgay($startDate, $endDate, $user, $type)
    {
        $StringArray = 'array';
        $StringObject = 'object';
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $qb->select(array(
                    'chungtu',
                    'nguoinopthue'
                ))
                    ->from('Application\Entity\chungtu', 'chungtu')
                    ->join('chungtu.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('usernnts.user = ?3')
                    ->andWhere('chungtu.NgayChungTu >= ?1')
                    ->andWhere('chungtu.NgayChungTu <= ?2')
                    ->setParameter(1, $startDate)
                    ->setParameter(2, $endDate)
                    ->setParameter(3, $user);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $qb->select(array(
                        'chungtu',
                        'nguoinopthue'
                    ))
                        ->from('Application\Entity\chungtu', 'chungtu')
                        ->join('chungtu.nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('user.parentUser = ?3')
                        ->andWhere('chungtu.NgayChungTu >= ?1')
                        ->andWhere('chungtu.NgayChungTu <= ?2')
                        ->setParameter(1, $startDate)
                        ->setParameter(2, $endDate)
                        ->setParameter(3, $user);
                }
            if ($type == $StringArray) {
                $obj = $qb->getQuery()->getArrayResult();
            } else 
                if ($type == $StringObject) {
                    $obj = $qb->getQuery()->getResult();
                }
            $kq->setObj($obj);
            $kq->setKq(true);
            $kq->setMessenger("Lấy danh sách chứng từ thành công !");
            return $kq;
        } catch (\Exception $e) {
            
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     * Ajax
     * param : Y-m-d
     * trả về danh sách (Array) chứng từ giữa 2 thời gian truyền vào | có thêm chitietchungtus
     *
     * @param string $startDate            
     * @param string $endDate            
     * @param user $user            
     * @param string $type            
     * @return \Application\Models\ketqua
     */
    public function DanhSachChungTuGiuaNgay2($startDate, $endDate, $user, $type)
    {
        $StringArray = 'array';
        $StringObject = 'object';
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $qb->select(array(
                    'chungtu',
                    'nguoinopthue',
                    'chitietchungtu',
                    'muclucngansach'
                ))
                    ->from('Application\Entity\chungtu', 'chungtu')
                    ->join('chungtu.nguoinopthue', 'nguoinopthue')
                    ->join('chungtu.chitietchungtus', 'chitietchungtu')
                    ->join('chitietchungtu.muclucngansach', 'muclucngansach')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('usernnts.user = ?3')
                    ->andWhere('chitietchungtu.TrangThai = 0')
                    ->andWhere('chungtu.NgayChungTu >= ?1')
                    ->andWhere('chungtu.NgayChungTu <= ?2')
                    ->setParameter(1, $startDate)
                    ->setParameter(2, $endDate)
                    ->setParameter(3, $user);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $qb->select(array(
                        'chungtu',
                        'nguoinopthue',
                        'chitietchungtu',
                        'muclucngansach'
                    ))
                        ->from('Application\Entity\chungtu', 'chungtu')
                        ->join('chungtu.nguoinopthue', 'nguoinopthue')
                        ->join('chungtu.chitietchungtus', 'chitietchungtu')
                        ->join('chitietchungtu.muclucngansach', 'muclucngansach')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('user.parentUser = ?3')
                        ->andWhere('chitietchungtu.TrangThai = 0')
                        ->andWhere('chungtu.NgayChungTu >= ?1')
                        ->andWhere('chungtu.NgayChungTu <= ?2')
                        ->setParameter(1, $startDate)
                        ->setParameter(2, $endDate)
                        ->setParameter(3, $user);
                }
            if ($type == $StringArray) {
                $obj = $qb->getQuery()->getArrayResult();
            } else 
                if ($type == $StringObject) {
                    $obj = $qb->getQuery()->getResult();
                }
            $kq->setObj($obj);
            $kq->setKq(true);
            $kq->setMessenger("Lấy danh sách chứng từ thành công !");
            return $kq;
        } catch (\Exception $e) {
            
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     * Trả về danh sách chi tiết chứng từ theo số chứng từ
     * SoChungTu: string
     *
     * @param string $SoChungTu            
     * @return \Application\Entity\ketqua
     */
    public function DanhSachChiTietChungTu($SoChungTu)
    {
        $kq = new ketqua();
        
        try {
            
            $obj = $qb = $this->em->createQueryBuilder()
                ->select(array(
                'chitietchungtu'
            ))
                ->from('Application\Entity\chitietchungtu', 'chitietchungtu')
                ->join('chitietchungtu.chungtu', 'chungtu')
                ->where('chungtu.SoChungTu = ?1')
                ->setParameter(1, $SoChungTu)
                ->getQuery()
                ->getArrayResult();
            if (count($obj) > 0) {
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Lấy danh sách chứng từ thành công !");
            } else {
                $kq->setObj($obj);
                $kq->setKq(false);
                $kq->setMessenger("Số chứng từ này không có chi tiết nào");
            }
            
            return $kq;
        } catch (\Exception $e) {
            
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     * Kiem tra so chung tu co thuoc quan ly cua user do khong
     *
     * SoChungTu: string
     *
     * @param string $SoChungTu            
     * @param user $user            
     * @return boolean
     */
    public function KiemTraSoChungCuaUser($SoChungTu, $user)
    {
        /* @var $ChungTu chungtu */
        $ChungTu = $this->em->find('Application\Entity\chungtu', $SoChungTu);
        $MaSoThue = $ChungTu->getNguoinopthue()->getMaSoThue();
        $nguoinopthueModel = new nguoinopthueModel($this->em);
        return $nguoinopthueModel->ktNNT($MaSoThue, $user);
    }

    public function Chambo($dsSoChungTu, $user)
    {
        $StringArray = 'array';
        $StringObject = 'object';
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {} else 
                if ($user->getLoaiUser() == 3) {
                    $this->em->getConnection()->beginTransaction();
                    
                    foreach ($dsSoChungTu as $SoChungTu) {
                        
                        /* @var $chungtu chungtu */
                        $chungtu = $this->em->find('Application\Entity\chungtu', $SoChungTu);
                        
                        if ($chungtu == null) {
                            $this->em->getConnection()->rollBack();
                            $kq->setKq(false);
                            $kq->setMessenger("Không tìm thấy số chứng từ $SoChungTu");
                            return $kq;
                        }
                        /* @var $nguoinopthue nguoinopthue */
                        $nguoinopthue = $chungtu->getNguoinopthue();
                        $MaSoThue = $nguoinopthue->getMaSoThue();
                        foreach ($chungtu->getChitietchungtus() as $chitietchungtu) {
                            
                            $KyThue = $chitietchungtu->getKyThue();
                            /* @var $muclucngansach $muclucngansach */
                            $muclucngansach = $chitietchungtu->getMuclucngansach();
                            $TieuMuc = $muclucngansach->getTieuMuc();
                            
                            if (substr($TieuMuc, 0, 2) == '18') {
                                /* @var $thuemb thuemonbai */
                                $thue = $this->em->find('Application\Entity\thuemonbai', array(
                                    'Nam' => explode('/', $KyThue)[1],
                                    'nguoinopthue' => $nguoinopthue
                                ));
                            } else {
                                $thue = $this->em->find('Application\Entity\thue', array(
                                    'KyThue' => $KyThue,
                                    'muclucngansach' => $muclucngansach,
                                    'nguoinopthue' => $nguoinopthue
                                ));
                            }
                            
                            if ($thue == null) {
                                
                                $this->em->getConnection()->rollBack();
                                $kq->setKq(false);
                                $kq->setMessenger("Không tìm thấy THUẾ $MaSoThue-$TieuMuc-$KyThue");
                                return $kq;
                            }
                            
                            if ($thue->getTrangThai() == 0) {
                            
                                $this->em->getConnection()->rollBack();
                                $kq->setKq(false);
                                $kq->setMessenger("THUẾ $MaSoThue-$TieuMuc-$KyThue chưa được duyệt !");
                                return $kq;
                            }
                            // kiem tra so tien
                            $SoTien = $this->getSoTienThue($KyThue, $MaSoThue, $TieuMuc);
                            if($SoTien == $chitietchungtu->getSoTien()){
                                $thue->setSoChungTu($chungtu->getSoChungTu());
                                $chitietchungtu->setTrangThai(1);
                                $this->em->merge($thue);
                                $this->em->merge($chitietchungtu);
                            }
                            else{
                                $this->em->getConnection()->rollBack();
                                $SoChungTu = $chungtu->getSoChungTu();
                                $kq->setKq(false);
                                $kq->setMessenger("Số chứng $SoChungTu có số tiền không khớp với số tiền trong sổ thuế !");
                                return $kq;
                            }
                        }
                    }
                    
                    $this->em->flush();
                    $this->em->getConnection()->commit();
                    $kq->setKq(true);
                    $kq->setMessenger("Tất cả chứng từ đã được chấm bộ thành công !");
                    return $kq;
                }
        } catch (\Exception $e) {
            // var_dump($e->getMessage());
            $this->em->getConnection()->rollBack();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     * Trả về số tiền phải đóng KyThue , MaSoThue , TieuMuc
     * @param string $KyThue            
     * @param string $MaSoThue            
     * @param string $TieuMuc            
     * @return integer|null
     */
    public function getSoTienThue($KyThue, $MaSoThue, $TieuMuc)
    {
        $SoTienThue = 0;
        $Nam = explode('/', $KyThue)[1];
        //mon bai
        if(substr($TieuMuc, 0,2)=='18'){
            $qb_monbai = $this->em->createQueryBuilder();
            $qb_monbai->select('thuemonbai.SoTien')
                ->from('Application\Entity\thuemonbai', 'thuemonbai')
                ->join('thuemonbai.nguoinopthue', 'nguoinopthue')
                ->join('thuemonbai.muclucngansach', 'muclucngansach')
                ->where('thuemonbai.KyThue = ?1')
                ->andWhere('nguoinopthue.MaSoThue = ?2')
                ->setParameter(1, $Nam)
                ->setParameter(2, $MaSoThue);
            $SoTienThue = $qb_monbai->getQuery()->getSingleResult()['SoTien'];
            return $SoTienThue;
        }
        else{
            $qb_Thue = $this->em->createQueryBuilder();
            $qb_Thue->select(array('thue.SoTien'))
            ->from('Application\Entity\thue', 'thue')
            ->join('thue.nguoinopthue', 'nguoinopthue')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->where('thue.KyThue = ?1')
            ->andWhere('muclucngansach.TieuMuc = ?2')
            ->andWhere('nguoinopthue.MaSoThue = ?3')
            ->setParameter(1, $KyThue)
            ->setParameter(2, $TieuMuc)
            ->setParameter(3, $MaSoThue);
            $Thue = $qb_Thue->getQuery()->getArrayResult();
            if(count($SoTienThue)>0 ){
                $SoTienThue += $Thue[0]['SoTien'];
            }
            
            
            $qb_TruyThu = $this->em->createQueryBuilder();
            
            $qb_TruyThu->select('truythu.SoTien')
            ->from('Application\Entity\truythu', 'truythu')
            ->join('truythu.thue', 'thue')
            ->join('thue.nguoinopthue', 'nguoinopthue')
            ->join('thue.muclucngansach', 'muclucngansach')
            ->where('thue.KyThue = ?1')
            ->andWhere('muclucngansach.TieuMuc = ?2')
            ->andWhere('nguoinopthue.MaSoThue = ?3')
            ->setParameter(1, $KyThue)
            ->setParameter(2, $TieuMuc)
            ->setParameter(3, $MaSoThue);
            
            $TruyThu = $qb_TruyThu->getQuery()->getArrayResult();
           
            if ($TruyThu != null) {
                $SoTienThue += $TruyThu['SoTien'];
            }
            
            $qb_MienGiam = $this->em->createQueryBuilder();
            $qb_MienGiam->select('kythuemg.SoTienMG')
            ->from('Application\Entity\kythuemg', 'kythuemg')
            ->join('kythuemg.miengiamthue', 'miengiamthue')
            ->join('miengiamthue.nguoinopthue', 'nguoinopthue')
            ->join('kythuemg.muclucngansach', 'muclucngansach')
            ->where('kythuemg.KyThue = ?1')
            ->andWhere('muclucngansach.TieuMuc = ?2')
            ->andWhere('nguoinopthue.MaSoThue = ?3')
            ->setParameter(1, $KyThue)
            ->setParameter(2, $TieuMuc)
            ->setParameter(3, $MaSoThue);
            $MienGiam = $qb_MienGiam->getQuery()->getArrayResult()[0];
            if ($MienGiam != null) {
                $SoTienThue -= $MienGiam['SoTienMG'];
            }
        }
        
        return $SoTienThue;
    }
}

