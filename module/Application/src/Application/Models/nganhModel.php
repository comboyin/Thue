<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\nguoinopthue;
class nganhModel extends baseModel
{
    /**
     * Trả về tỷ lệ tính thuế theo mã ngành và tiểu muc
     * @param string $MaNganh
     * @param string $TieuMuc
     * @return \Doctrine\ORM\mixed|NULL  */
    public function getTyLeTinhThueMaNganh_TieuMuc($MaNganh,$TieuMuc){
        try {
            return $this->em->createQueryBuilder()
                            ->select("bieuthuetyle.TyLeTinhThue")
                            ->from('Application\Entity\bieuthuetyle', 'bieuthuetyle')
                            ->join('bieuthuetyle.nganh', 'nganh')
                            ->join('bieuthuetyle.muclucngansach', 'muclucngansach')
                            ->where('nganh.MaNganh = ?1')
                            ->andwhere('muclucngansach.TieuMuc = ?2')
                            ->setParameter(1, $MaNganh)
                            ->setParameter(2, $TieuMuc)
                            ->getQuery()
                            ->getSingleResult()['TyLeTinhThue'];
            
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
        return  null;
    }
    
    public function getTyLeTinhThueMaSoThue_TieuMuc($MaSoThue,$TieuMuc){
        try {
            /* @var $nguoinopthue nguoinopthue */
            $nguoinopthue = $this->em->find('Application\Entity\nguoinopthue', $MaSoThue);
            $MaNganh = $nguoinopthue->getNNTNganh()->getNganh()->getMaNganh();
            return $this->getTyLeTinhThueMaNganh_TieuMuc($MaNganh, $TieuMuc);
            
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
        
        return null;
    }
}

?>