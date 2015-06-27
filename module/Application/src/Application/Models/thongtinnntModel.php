<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\user;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Application\Entity\ketqua;
use Application\Entity\thongtinnnt;

class thongtinnntModel extends baseModel
{
    /**
     * đối số là mã số thuế
     * trả về thongtinnnt đang hoạt động của HKD có mã số thuế đó
     * @param string $MaSoThue  
     * @return ketqua*/
    public function ThongtinnntDangHoatDong($MaSoThue)
    {
        try {
            
            
            $thongtinnnt = $this->em->createQueryBuilder()
                ->select("thongtinnnt")
                ->from("Application\Entity\\thongtinnnt", "thongtinnnt")
                ->join("thongtinnnt.nguoinopthue", "nguoinopthue")
                ->where("nguoinopthue.MaSoThue = ?1")
                ->andWhere("thongtinnnt.ThoiGianKetThuc is null")
                
                ->setParameter(1, $MaSoThue)
                ->getQuery()
                ->getSingleResult();
            
            $this->kq->setKq(true);
            $this->kq->setObj($thongtinnnt);
            return $this->kq;
           
        } catch (\Exception $e) {
            
            
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            $this->kq->setObj(null);
            
            return $this->kq;
        }
    }
    
    /**
     * 
     * Thay đổi thông tin người nộp thuế
     * @param thongtinnnt $thongtinnntOld
     * @param thongtinnnt $thongtinnntNew
     * @param string $MaSoThue
     * @return ketqua*/
    public function ThayDoiDiaChiKD($thongtinnntOld,$thongtinnntNew,$MaSoThue)
    {
        $kq=new ketqua();
        try {
            
            //giả sử tất của quá trình đều đúng
            $kq->setKq(true);
            $kq->setMessenger("Thay đổi địa chỉ thành công !");
            
            //Sua thoigianketthuc = null 

            $this->merge($thongtinnntOld);
            if($this->ThongtinnntDangHoatDong($MaSoThue)->getObj()==null){
                //them thong tin thay doi
                $this->them($thongtinnntNew);
                if($this->ThongtinnntDangHoatDong($MaSoThue)->getObj()==null){
                    $thongtinnntOld->setThoiGianKetThuc(null);
                    $this->merge($thongtinnntOld);
                    $kq->setKq(false);
                    $kq->setMessenger("Thay đổi chỉ thất bại, trong lúc thêm mới địa chỉ gặp phải lỗi.");
                }
                
            }
            else{
                $kq->setKq(false);
                $kq->setMessenger("Thay đổi địa chỉ thất bại. Thời gian kết thúc kinh doanh tại địa chỉ củ không được hoàn tất !");
            }
    
           
           return $kq;  
        } catch (\Exception $e) {
            
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
}
