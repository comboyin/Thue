<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\thuemonbai;
use Application\Entity\dukienmb;
use Application\Unlity\Unlity;

class thuemonbaiModel extends baseModel
{

    public function dsThueMonBai($thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'thuemonbai',
                    'nguoinopthue',
                    'usernnts',
                    'muclucngansach'
                )
                )
                    ->from('Application\Entity\thuemonbai', 'thuemonbai')
                    ->join('thuemonbai.muclucngansach', 'muclucngansach')
                    ->join('thuemonbai.nguoinopthue', 'nguoinopthue')
                    ->
                join('nguoinopthue.usernnts', 'usernnts')
                    ->
                where('thuemonbai.Nam = ?1')
                    ->andWhere('usernnts.user = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    //->andWhere("muclucngansach.TieuMuc like '180_'")
                    ->setParameter(2, $user)
                    ->setParameter(1, $thang);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'thuemonbai',
                        'nguoinopthue',
                        'usernnts',
                        'muclucngansach'
                    )
                    )
                        ->from('Application\Entity\thuemonbai', 'thuemonbai')
                        ->join('thuemonbai.muclucngansach', 'muclucngansach')
                        ->join('thuemonbai.nguoinopthue', 'nguoinopthue')
                        ->
                    join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('thuemonbai.Nam = ?1')
                        ->andWhere('user.parentUser = ?2')
                        ->andWhere('usernnts.ThoiGianKetThuc is null')
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
            
            $this->kq->setMessenger('Lấy danh sách thuế môn bài ' . $thang . ' thành công !');
            return $this->kq;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }

    public function ghiso($dsMaSoThue, $Nam)
    {
        $kq = new ketqua();
        $dem = 0;
        
        try {
            $this->em->getConnection()->beginTransaction();
            foreach ($dsMaSoThue as $key => $value) {
                // tim du kien mon bai
                $dukienthuemonbai = $this->em->find('Application\Entity\dukienmb', array(
                    'nguoinopthue' => $this->em->find('Application\Entity\nguoinopthue', $value),
                    //'muclucngansach' => $this->em->find('Application\Entity\muclucngansach', $dsTieuMuc[$key]),
                    'Nam' => $Nam
                ));
                if ($dukienthuemonbai == null) {
                    $this->em->getConnection()->rollBack();
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red">' . "Không tìm thấy dự kiến thuế " . $value . " - " . $Nam . '</span>');
                    return $kq;
                }
                /* @var $dukienthuemonbai dukienmb */
                $thuemonbai = new thuemonbai();
                $thuemonbai->setNguoinopthue($this->em->find('Application\Entity\nguoinopthue', $value));
                $thuemonbai->setMuclucngansach($dukienthuemonbai->getMuclucngansach());
                $thuemonbai->setNam($Nam);
                
                $thuemonbai->setDoanhSo($dukienthuemonbai->getDoanhSo());
                $thuemonbai->setSoTien($dukienthuemonbai->getSoTien());
                $thuemonbai->setNgayPhaiNop(Unlity::ConverDate('d-m-Y', $dukienthuemonbai->getNgayPhaiNop(), 'Y-m-d')); // 2015-07-28
                $thuemonbai->setTrangThai(0);
                
                //set trạng thái cho dự kiến môn bài - đã ghi
                //$dukienthuethang->setTrangThai(1);
                $this->em->persist($thuemonbai);
                //$this->em->merge($dukienthuemonbai);
                $this->em->flush();
                $dem ++;
            }
            
            $kq->setKq(true);
            $kq->setMessenger('<span style="color:green">' . 'Tẩt cả dự kiến môn bài ' . $Nam . ' được chọn đã ghi sổ thành công <br/>
                                Tổng cộng có ' . $dem . ' dự kiến môn bài được ghi sổ !' . '</span>');
            
            $this->em->getConnection()->commit();
            
            return $kq;
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            $kq->setKq(false);
            $kq->setMessenger('<span style="color:red">' . $e->getMessage() . '</span>');
            return $kq;
        }
    }
    public function duyet($dsMaSoThue,$Nam){
        $kq= new ketqua();
        $dem =0;
        
        try {
            $this->em->getConnection()->beginTransaction();
            foreach ($dsMaSoThue as $key=>$value){
                $MaSoThue = $value;
                //$TieuMuc = $dsTieuMuc[$key];
                
                /* @var $thuemonbai thuemonbai */
                $thuemonbai = $this->em->find('Application\Entity\thuemonbai', array(
                    'nguoinopthue'=>$this->em->find('Application\Entity\nguoinopthue', $MaSoThue),
                    //'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $TieuMuc),
                    'Nam' => $Nam
                ));
        
                // khong tim thay
                if($thuemonbai==null){
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red;" >'."Không tìm thấy ".$MaSoThue.'-'.$Nam.'<br/></span>');
        
                    $this->em->getConnection()->rollBack();
                    return $kq;
                }
        
                //thuemonbai da duoc duyet
                if($thuemonbai->getTrangThai()==1){
                    $kq->setKq(false);
                    $kq->setMessenger('<span style="color:red;" >'."Thuế môn bài".$MaSoThue.'-'.$Nam . " Đã được duyệt ! Vui lòng kiểm tra và thử lại !".'<br/></span>');
        
                    $this->em->getConnection()->rollBack();
                    return $kq;
                }
        
                $thuemonbai->setTrangThai(1);
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
     * @param string $nam
     * @param string $masothue
     * @param string $user
     * @param string $tieumuc
     * @return ketqua
     */
    public function findByID_($nam, $masothue)
    {
        /* @var $user user */
        try {
            $kq = new ketqua();
            $qb = $this->em->createQueryBuilder();
    
            $qb->select('thuemonbai')
            ->from('Application\Entity\thuemonbai', 'thuemonbai')
            ->join('thuemonbai.nguoinopthue', 'nguoinopthue')
            //->join('thuemonbai.muclucngansach', 'muclucngansach')
            ->where('nguoinopthue.MaSoThue = ?1')
            //->andWhere('muclucngansach.TieuMuc = ?2')
            ->andWhere('thuemonbai.Nam = ?2')
            ->setParameter(2, $nam)
    
            ->setParameter(1, $masothue);
            //->setParameter(2, $tieumuc);
    
            $kq->setObj($qb->getQuery()
                ->getSingleResult());
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