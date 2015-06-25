<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\user;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Application\Entity\ketqua;
use Application\Entity\miengiamthue;

class miengiamthueModel extends baseModel
{
    
    public function abc()
    {
        try {
            /* @var $mg miengiamthue */
            $mg = $this->em->createQueryBuilder()->select("miengiamthue")
                        ->from("Application\Entity\miengiamthue", "miengiamthue")
                        ->getQuery()->getResult();
          
            var_dump($mg);
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
        }
    }
}
