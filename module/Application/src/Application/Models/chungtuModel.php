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
                            
                            if(substr($TieuMuc, 0,2)=='18'){
                                    /* @var $thuemb thuemonbai */ 
                                $thue = $this->em->find('Application\Entity\thuemonbai', array(
                                'Nam' => explode('/', $KyThue)[1],
                                'nguoinopthue' => $nguoinopthue
                                ));
                               
                                
                                
                                
                            }else{
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
                            
                            $thue->setSoChungTu($chungtu->getSoChungTu());
                            $chitietchungtu->setTrangThai(1);
                            $this->em->merge($thue);
                            $this->em->merge($chitietchungtu);
                             
                           
                            
                            
                            
                        }
                    }
                    
                    $this->em->flush();
                    $this->em->getConnection()->commit();
                    $kq->setKq(true);
                    $kq->setMessenger("Tất cả chứng từ đã được chấm bộ thành công !");
                    return $kq;
                }
            
           
        } catch (\Exception $e) {
            //var_dump($e->getMessage());
            $this->em->getConnection()->rollBack();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
}

