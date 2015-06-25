<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\user;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Application\Entity\ketqua;

class phuongModel extends baseModel
{
    /**
     * đối số là mã quận
     * trả về danh sách phường thuộc quận đó
     * @param string $MaQuan  */
    public function DanhSachPhuongThuocQuan($MaQuan)
    {
        try {
            
            $phuong = $this->em->createQueryBuilder()
                ->select("phuong")
                ->from("Application\Entity\phuong", "phuong")
                ->join("phuong.coquanthue", 'doithue')
                ->join("doithue.chicucthue", "chicucthue")
                ->where("chicucthue.MaCoQuan = ?1")
                ->setParameter(1, $MaQuan)
                ->getQuery()
                ->getResult();
            $this->kq->setKq(true);
            $this->kq->setObj($phuong);
            return $this->kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
        }
    }
}
