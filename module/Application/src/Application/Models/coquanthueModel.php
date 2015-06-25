<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\user;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Application\Entity\ketqua;
use Application\Entity\coquanthue;

class coquanthueModel extends baseModel
{
/**
 * Trả về danh sách chi cục thuế
 * @return coquanthue
 *   */
    public function DanhSachChiCucThue()
    {
        $chicucthue = $this->em->createQueryBuilder()
            ->select('coquanthue')
            ->from("Application\Entity\coquanthue", "coquanthue")
            ->where("coquanthue.chicucthue is null")
            ->getQuery()->getResult();
        return $chicucthue;
    }
}
