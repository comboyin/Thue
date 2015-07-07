<?php
namespace Quanlynguoinopthue\Models;

use Application\base\baseModel;
use Application\Entity\user;
use Application\Entity\ketqua;
use Application\Entity\nguoinopthue;
use Application\Entity\NNTNganh;

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
     * Trả về danh sách người nộp thuế của cán bộ thuế đang quản lý
     * Bao gồm :
             * + Người nộp thuế tạm ngừng kinh doanh
             * + Người nộp thuế đang hoạt động
             * + Người nộp thuế ngưng kinh doanh
     * 
     * $type: 
     *          + array
     *          + object
     * @param user $user         
     * @param string $type   
     * @return \Application\Entity\ketqua
     */
    public function DanhSachByIdentity($user,$type)
    {
        $StringArray = "array";
        $StringObject = "object";
        // cbt
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        $ma = $user->getMaUser();
        
        try {
            

           
            
            if ($user->getLoaiUser() == 4) {
                
                $DQL_HKD_NghiKD = $this->em->createQueryBuilder()
                ->select('nguoinopthue1')
                ->from('Application\Entity\nguoinopthue', 'nguoinopthue1')
                ->join('nguoinopthue1.usernnts', 'usernnts1')
                ->join('nguoinopthue1.thongtinngungnghis', 'thongtinngungnghis')
                ->where('usernnts1.user = ?1')
                ->andWhere('thongtinngungnghis.DenNgay is null')
                ->getDQL();
                
                
                $qb->select(array(
                    'nguoinopthue'
                ))
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('user = ?1')
                    ->andWhere("usernnts.ThoiGianKetThuc is null OR nguoinopthue in ($DQL_HKD_NghiKD)")
                    ->setParameter(1, $user);
            } else 
                if ($user->getLoaiUser() == 3) {
                    
                    // Danh sách cán bộ viên của thuộc quản lý của đội trưởng
                    $DQL_CanBoVien =  $this->em->createQueryBuilder()
                    ->select("canbovien")
                    ->from('Application\Entity\user', 'canbovien')
                    ->where('canbovien.parentUser = ?1')
                    ->getDQL();
                    
                    
                    $DQL_HKD_NghiKD = $this->em->createQueryBuilder()
                    ->select('nguoinopthue1')
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue1')
                    ->join('nguoinopthue1.thongtinngungnghis', 'thongtinngungnghis')
                    ->join('nguoinopthue1.usernnts', 'usernnts1')
                    ->join('usernnts1.user', 'user1')
                    ->andWhere('user1.parentUser = ?1')
                    ->andWhere('thongtinngungnghis.DenNgay is null')
                    ->getDQL();
                    
                    
                    $qb->select(array(
                        'nguoinopthue'
                    ))
                        ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->andWhere('user.parentUser = ?1')
                        ->where("usernnts.ThoiGianKetThuc is null OR nguoinopthue in ($DQL_HKD_NghiKD)")
                        ->andwhere("user in ($DQL_CanBoVien)")
                        ->setParameter(1, $user);
                }
            if($type==$StringArray){
                $obj = $qb->getQuery()->getArrayResult();
            }else if($type==$StringObject){
                $obj = $qb->getQuery()->getResult();
            }
            
            
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
     * json
     * Trả về danh sách người nộp thuế của cán bộ thuế đang quản lý, đang kinh doanh
     *
     * @param user $user            
     * @return string
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
                    ->where('user in (' . $this->em->createQueryBuilder()
                    ->select("canbovien")
                    ->from('Application\Entity\user', 'canbovien')
                    ->where('canbovien.parentUser = ?1')
                    ->getDQL() . ')')
                    ->setParameter(1, $user)
                    ->andwhere("usernnts.ThoiGianKetThuc is null");
                
                return json_encode($bq->getQuery()->getArrayResult());
            }
    }

    /**
     * array
     * Trả về danh sách người nộp thuế của cán bộ thuế đang quản lý, đang kinh doanh
     *
     * @param user $user            
     * @return \Application\Entity\ketqua
     */
    private function dsNNTbyUser2($user)
    {
        // cbt
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        $ma = $user->getMaUser();
        
        try {
            if ($user->getLoaiUser() == 4) {
                
                $qb->select('nguoinopthue')
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('user = ?1')
                    ->andwhere("usernnts.ThoiGianKetThuc is null")
                    ->setParameter(1, $user);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $qb->select(array(
                        'nguoinopthue'
                    ))
                        ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('user in (' . $this->em->createQueryBuilder()
                        ->select("canbovien")
                        ->from('Application\Entity\user', 'canbovien')
                        ->where('canbovien.parentUser = ?1')
                        ->getDQL() . ')')
                        ->andwhere("usernnts.ThoiGianKetThuc is null")
                        ->setParameter(1, $user);
                }
            
            $obj = $qb->getQuery()->getResult();
            
            $kq->setObj($obj);
            $kq->setKq(true);
            $kq->setMessenger("Lấy danh sách người nộp thuế thành công");
            
            return $kq;
        } catch (\Exception $e) {
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }

    /**
     *
     * Kiểm tra xem nnt có trong danh sách người nộp thuế của cán bộ thuế đang quản lý, đang kinh doanh hay ko
     *
     * @param string $mst            
     * @param user $user            
     * @return boolean
     */
    public function ktNNT($mst, $user)
    {
        $dsnnt = $this->dsNNTbyUser2($user)->getObj();
        if ($dsnnt != null) {
            foreach ($dsnnt as $nnt) {
                if ($nnt->getMaSoThue() == $mst)
                    return true;
            }
        } else {
            return false;
        }
        return false;
    }

    /**
     * Câp nhật ngành .
     *
     * sửa nntnganh cu
     * và thêm mới nntnganh mới
     * nếu xảy ra lỗi sẽ rollback và đọc lại trong csdl cập nhật nguoinopthue
     * 
     * @param NNTNganh $nntnganhOld            
     * @param NNTNganh $nntnganhNew            
     * @param nguoinopthue $nguoinopthue            
     * @return \Application\Entity\ketqua
     */
    public function capNhatNganh($nntnganhOld, $nntnganhNew, &$nguoinopthue)
    {
        $kq = new ketqua();
        try {
            
            $this->em->getConnection()->beginTransaction();
            $this->em->merge($nntnganhOld);
            $this->em->persist($nntnganhNew);
            
            $kq->setObj($nntnganhNew);
            
            // appent messanger
            $kq->setKq(true);
            $kq->appentMessenger("Cập nhật ngành thành công !");
            $kq->appentMessenger("Thông tin cập nhật như sau: ");
            $kq->appentMessenger('Từ ');
            $kq->appentMessenger($nntnganhOld->getNganh()
                ->getMaNganh() . ' - ' . $nntnganhOld->getNganh()
                ->getTenNganh());
            $kq->appentMessenger('thành');
            $kq->appentMessenger($nntnganhNew->getNganh()
                ->getMaNganh() . ' - ' . $nntnganhNew->getNganh()
                ->getTenNganh());
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            
            $nguoinopthue = $this->em->find('Application\Entity\nguoinopthue', $nguoinopthue->getMaSoThue());
            
            $kq->setKq(false);
            $kq->setMessenger('Thất bại trong việc cập nhật ngành !');
            $kq->appentMessenger($e->getMessage());
            
            $this->em->getConnection()->rollBack();
        }
        
        return $kq;
    }

    public function capNhatCanBoQuanLy($UsernntCu, $UsernntNew, &$nguoinopthue)
    {
        $kq = new ketqua();
        try {
            
            $this->em->getConnection()->beginTransaction();
            $this->em->merge($UsernntCu);
            $this->em->persist($UsernntNew);
            
            // appent messanger
            $kq->setKq(true);
            $kq->appentMessenger("Cập nhật cán bộ thành công !");
            $this->em->flush();
            $this->em->commit();
            $kq->setObj($UsernntNew);
        } catch (\Exception $e) {
            
            $nguoinopthue = $this->em->find('Application\Entity\nguoinopthue', $nguoinopthue->getMaSoThue());
            
            $kq->setKq(false);
            $kq->setMessenger('Thất bại trong việc cập nhật ngành !');
            $kq->appentMessenger($e->getMessage());
            
            $this->em->getConnection()->rollBack();
        }
        
        return $kq;
    }
}

?>