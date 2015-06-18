<?php
namespace Application\base;


use Application;

use Application\Entity\ketqua;

abstract class baseModel
{
    /**
     * 
     * @var \Doctrine\ORM\EntityManager  */
    protected $em;
    /**@var ketqua  */
    protected $kq=null;
    
    
    
    public function __construct(\Doctrine\ORM\EntityManager $em){
        $this->em = $em;
        if($this->kq==null)
        {
            $this->kq = new ketqua();
        }
      
    }
    
    public function them($obj){
        try {
            
            $this->em->persist($obj);
            $this->em->flush();
            $kq = new ketqua();
            $kq->setKq(true);
            $kq->setMessenger('Thêm thành công !');
        
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    public function merge($obj){
        try {
    
            $this->em->merge($obj);
            $this->em->flush();
            $kq = new ketqua();
            $kq->setKq(true);
            $kq->setMessenger('Sửa thành công !');
    
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    public function remove($obj){
        try {
    
            $this->em->remove($obj);
            $this->em->flush();
            $kq = new ketqua();
            $kq->setKq(true);
            $kq->setMessenger('Xóa thành công !');
    
            return $kq;
        } catch (\Exception $e) {
            $kq = new ketqua();
            $kq->setKq(false);
            $kq->setMessenger($e->getMessage());
            return $kq;
        }
    }
    
    
    
    abstract function xoa($id);
    abstract function sua($id);
    abstract function findById($id);
    abstract function getDanhSach();
  
}

?>