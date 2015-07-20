<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\user;
use Application\Entity\miengiamthue;
use Quanlynguoinopthue\Models\nguoinopthueModel;

class miengiamModel extends baseModel
{

    /** Ajax
     * param : Y-m-d
     * trả về danh sách (Array) miễn giảm giữa 2 thời gian truyền vào
     * @param string $startDate            
     * @param string $endDate      
     * @param user  $user      
     * @param string $type
     * @return \Application\Models\ketqua
     */
    public function DanhSachMienGiamGiuaNgay($startDate, $endDate,$user,$type)
    {
        $StringArray = 'array';
        $StringObject = 'object';
        $kq = new ketqua();
        $qb = $this->em->createQueryBuilder();
        try {
            if($user->getLoaiUser()==4){
               $qb->select(array('miengiamthue','nguoinopthue'))
                ->from('Application\Entity\miengiamthue', 'miengiamthue')
                ->join('miengiamthue.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->where('usernnts.user = ?3')
                ->andWhere('miengiamthue.NgayCoHieuLuc >= ?1')
                ->andWhere('miengiamthue.NgayCoHieuLuc <= ?2')
                ->setParameter(1, $startDate)
                ->setParameter(2, $endDate)
                ->setParameter(3, $user);
            }
            else if($user->getLoaiUser()==3){
                $qb->select(array('miengiamthue','nguoinopthue'))
                ->from('Application\Entity\miengiamthue', 'miengiamthue')
                ->join('miengiamthue.nguoinopthue', 'nguoinopthue')
                ->join('nguoinopthue.usernnts', 'usernnts')
                ->join('usernnts.user', 'user')
                ->where('user.parentUser = ?3')
                ->andWhere('miengiamthue.Ngaymiengiamthue >= ?1')
                ->andWhere('miengiamthue.Ngaymiengiamthue <= ?2')
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
            $kq->setMessenger("Lấy danh sách miễn giảm thành công !");
            return $kq;
        } catch (\Exception $e) {
            
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    /**
     * Trả về danh sách chi tiết miễn giảm theo số miễn giảm
     * SoQDMG: string
     * @param string $SoQDMG
     * @return \Application\Entity\ketqua  */
    public function DanhSachChiTietMienGiam($SoQDMG)
    {
        $kq = new ketqua();
        
        try {
        
            $obj = $qb = $this->em->createQueryBuilder()->
            select(array('kythuemg'))
            ->from('Application\Entity\kythuemg', 'kythuemg')
            ->join('kythuemg.miengiamthue', 'miengiamthue')
            ->where('miengiamthue.SoQDMG = ?1')
            ->setParameter(1,$SoQDMG)
            ->getQuery()
            ->getArrayResult();
            if(count($obj)>0){
                $kq->setObj($obj);
                $kq->setKq(true);
                $kq->setMessenger("Lấy danh sách miễn giảm thành công !");
            }
            else{
                $kq->setObj($obj);
                $kq->setKq(false);
                $kq->setMessenger("Số quyết định miễn giảm này không có chi tiết nào");
            }
            
            return $kq;
        } catch (\Exception $e) {
        
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    
    /**
     * Kiem tra so quyet dinh mien giam co thuoc quan ly cua user do khong
     * 
     * SoQDMG: string
     * @param string $SoQDMG
     * @param user $user
     * @return boolean  */
    public function KiemTraSoQDMGCuaUser($SoQDMG,$user)
    {
        /* @var $miengiamthue miengiamthue */
        $miengiamthue = $this->em->find('Application\Entity\miengiamthue', $SoQDMG);
        $MaSoThue = $miengiamthue->getNguoinopthue()->getMaSoThue();
        $nguoinopthueModel = new nguoinopthueModel($this->em);
        return $nguoinopthueModel->ktNNT($MaSoThue, $user);
    }
    

}

