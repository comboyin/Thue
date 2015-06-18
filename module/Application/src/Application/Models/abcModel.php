<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\Thanhvien;

class abcModel extends baseModel
{

    /**
     *
     * @param Thanhvien $Thanhvien            
     * @return boolean
     *
     */
    public function them($Thanhvien)
    {
        try {
            /* @var $Thanhvien Thanhvien */
            // TODO Auto-generated method stub
            $this->getEm()->persist($Thanhvien);
            $this->getEm()->flush();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /*
     * (non-PHPdoc)
     * @see \Application\base\baseModel::findById()
     */
    public function findById()
    {
        // TODO Auto-generated method stub
    }

    /**
     *
     * @return \Application\Entity\Thanhvien|NULL
     *
     */
    public function getDanhSach()
    {
        // TODO Auto-generated method stub
        try {
            $query = $this->getEm()->createQuery('select n from Application\Entity\Thanhvien n');
            /* @var $thanhviens Thanhvien[] */
            $thanhviens = $query->getresult();
            return $thanhviens;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /*
     * (non-PHPdoc)
     * @see \Application\base\baseModel::sua()
     */
    public function sua()
    {
        // TODO Auto-generated method stub
    }

    /*
     * (non-PHPdoc)
     * @see \Application\base\baseModel::xoa()
     */
    public function xoa($id)
    {
        // TODO Auto-generated method stub
    }
}

?>