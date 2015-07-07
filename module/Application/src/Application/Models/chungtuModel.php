<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;
use Application\Entity\chungtu;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class chungtuModel extends baseModel
{

    /** Ajax
     * param : Y-m-d
     * trả về danh sách (Array) chứng từ giữa 2 thời gian truyền vào
     * @param string $startDate            
     * @param string $endDate      
     * @param user  $user      
     * @param string $type
     * @return \Application\Models\ketqua
     */
    public function DanhSachChungTuGiuaNgay($startDate, $endDate,$user,$type)
    {
        $StringArray = 'array';
        $StringObject = 'object';
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        try {
            if($user->getLoaiUser()==4){
               $qb->select(array('chungtu','nguoinopthue'))
                ->from('Application\Entity\chungtu', 'chungtu')
                ->join('chungtu.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->where('usernnts.user = ?3')
                ->andWhere('chungtu.NgayChungTu >= ?1')
                ->andWhere('chungtu.NgayChungTu <= ?2')
                ->setParameter(1, $startDate)
                ->setParameter(2, $endDate)
                ->setParameter(3, $user);
            }
            else if($user->getLoaiUser()==3){
                $qb->select(array('chungtu','nguoinopthue'))
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
            if($type==$StringArray)
            {
                $obj = $qb->getQuery()->getArrayResult();
            }else if($type==$StringObject){
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
     * @param string $SoChungTu
     * @return \Application\Entity\ketqua  */
    public function DanhSachChiTietChungTu($SoChungTu)
    {
        $kq = new ketqua();
        
        try {
        
            $obj = $qb = $this->em->createQueryBuilder()->
            select(array('chitietchungtu'))
            ->from('Application\Entity\chitietchungtu', 'chitietchungtu')
            ->join('chitietchungtu.chungtu', 'chungtu')
            ->where('chungtu.SoChungTu = ?1')
            ->setParameter(1,$SoChungTu)
            ->getQuery()
            ->getArrayResult();
            if(count($obj)>0){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Lấy danh sách chứng từ thành công !");
            }
            else{
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
     * @param string $SoChungTu
     * @param user $user
     * @return boolean  */
    public function KiemTraSoChungCuaUser($SoChungTu,$user)
    {
        /* @var $ChungTu chungtu */
        $ChungTu = $this->em->find('Application\Entity\chungtu', $SoChungTu);
        $MaSoThue = $ChungTu->getNguoinopthue()->getMaSoThue();
        $nguoinopthueModel = new nguoinopthueModel($this->em);
        return $nguoinopthueModel->ktNNT($MaSoThue, $user);
    }
    

}

