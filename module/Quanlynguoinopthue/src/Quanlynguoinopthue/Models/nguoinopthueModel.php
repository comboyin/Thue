<?php
namespace Quanlynguoinopthue\Models;

use Application\base\baseModel;
use Application\Entity\user;
use Application\Entity\ketqua;
use Application\Entity\nguoinopthue;
use Doctrine\DBAL\Types\BooleanType;

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
                    'nguoinopthue'
                )
                )
                    ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->join('usernnts.user', 'user')
                    ->where('user = ?1')
                    ->setParameter(1, $user);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $qb->select(array(
                        'nguoinopthue'
                    ))
                        ->from('Application\Entity\nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('user in ('.$this->em->createQueryBuilder()->select("canbovien")
                                                ->from('Application\Entity\user', 'canbovien')
                                                ->where('canbovien.parentUser = ?1')->getDQL()
                                                .')')
                        ->setParameter(1, $user);
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
     * json
     * Trả về danh sách người nộp thuế của cán bộ thuế đang quản lý, đang kinh doanh
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
                                            ->where('user in ('.$this->em->createQueryBuilder()->select("canbovien")
                                                ->from('Application\Entity\user', 'canbovien')
                                                ->where('canbovien.parentUser = ?1')->getDQL()
                                                .')')
                    ->setParameter(1, $user)
                    ->andwhere("usernnts.ThoiGianKetThuc is null");
                    
                return json_encode($bq->getQuery()->getArrayResult());
            }
    }
    
    /**
     * array
     * Trả về danh sách người nộp thuế của cán bộ thuế đang quản lý, đang kinh doanh
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
                
                $qb->select(
                    'nguoinopthue'
                
                )
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
                        ->where('user in ('.$this->em->createQueryBuilder()->select("canbovien")
                                                ->from('Application\Entity\user', 'canbovien')
                                                ->where('canbovien.parentUser = ?1')->getDQL()
                                                .')')
                        ->andwhere("usernnts.ThoiGianKetThuc is null")
                        ->setParameter(1, $user);
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
     * 
     * Kiểm tra xem nnt có trong danh sách người nộp thuế của cán bộ thuế đang quản lý, đang kinh doanh hay ko
     * @param string $mst
     * @param user $user
     * @return BooleanType
     */
    public function ktNNT($mst, $user)
    {
        $dsnnt = $this->dsNNTbyUser2($user)->getObj();
        if($dsnnt!=null)
        {
            foreach ($dsnnt as $nnt)
            {
                if($nnt->getMaSoThue() == $mst)
                    return true;
            }
        }
        else 
        {
            return false;
        }
        return false;
    }
    
    
}

?>