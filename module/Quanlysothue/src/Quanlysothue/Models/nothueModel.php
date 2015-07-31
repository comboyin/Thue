<?php
namespace Quanlysothue\Models;

use Application\base\baseModel;
use Application\Entity\ketqua;
use Application\Entity\sono;
use Application\Unlity\Unlity;

class nothueModel extends baseModel
{

    public function dsNoThue($thang, $user, $type)
    {
        $q = $this->em->createQueryBuilder();
        try {
            if ($user->getLoaiUser() == 4) {
                $q->select(array(
                    'sono',
                    'nguoinopthue',
                    'muclucngansach',
                    'usernnts'
                )
                )
                    ->from('Application\Entity\sono', 'sono')
                    ->join('sono.muclucngansach', 'muclucngansach')
                    ->join('sono.nguoinopthue', 'nguoinopthue')
                    ->join('nguoinopthue.usernnts', 'usernnts')
                    ->where('sono.KyLapBo = ?1')
                    ->andWhere('usernnts.user = ?2')
                    ->andWhere('usernnts.ThoiGianKetThuc is null')
                    ->setParameter(2, $user)
                    ->setParameter(1, $thang);
            } else 
                if ($user->getLoaiUser() == 3) {
                    $q->select(array(
                        'sono',
                        'nguoinopthue',
                        'muclucngansach',
                        'usernnts'
                    )
                    )
                        ->from('Application\Entity\sono', 'sono')
                        ->join('sono.muclucngansach', 'muclucngansach')
                        ->join('sono.nguoinopthue', 'nguoinopthue')
                        ->join('nguoinopthue.usernnts', 'usernnts')
                        ->join('usernnts.user', 'user')
                        ->where('sono.KyLapBo = ?1')
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
            
            $this->kq->setMessenger('Lấy danh sách nợ thuế ' . $thang . ' thành công !');
            return $this->kq;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->kq->setKq(false);
            $this->kq->setMessenger($e->getMessage());
            return $this->kq;
        }
    }

    /**
     *
     * @param string $kylapbo
     * @param string $kythue
     * @param string $masothue
     * @param string $tieumuc
     * @return ketqua
     */
    public function findByID_($kylapbo, $kythue, $masothue, $tieumuc)
    {
        /* @var $user user */
        try {
            $kq = new ketqua();
            $obj = $this->em->find('Application\Entity\sono', array(
                'KyLapBo'=>$kylapbo,
                'KyThue'=>$kythue,
                'nguoinopthue'=>$this->em->find('Application\Entity\nguoinopthue', $masothue),
                'muclucngansach'=>$this->em->find('Application\Entity\muclucngansach', $tieumuc)
            ));
            $kq->setObj($obj);
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