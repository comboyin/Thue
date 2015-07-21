<?php
namespace Application\Entity;

class ketqua
{

    private $kq = FALSE;

    private $messenger = '';

    private $obj;

    public function toArray()
    {
        $kq = array(
            'kq'=>$this->kq,
            'messenger'=>$this->messenger,
            'obj'=>$this->obj
        );
        return $kq;
    }
    /**
     * Nối chuổi mesenger
     * @param string $stringMess  */
    public function appentMessenger($stringMess){
        $this->messenger = $this->messenger.'<br/>'.$stringMess;
    }

    /**
     *
     * @return the $obj
     */
    public function getObj()
    {
        return $this->obj;
    }

    /**
     *
     * @param field_type $obj            
     */
    public function setObj($obj)
    {
        $this->obj = $obj;
    }

    public function __construct()
    {
        $this->kq = '';
        $this->messenger = '';
        $this->obj = null;
    }

    /**
     *
     * @return the $kq
     */
    public function getKq()
    {
        return $this->kq;
    }

    /**
     *
     * @return the $messenger
     */
    public function getMessenger()
    {
        return $this->messenger;
    }

    /**
     *
     * @param field_type $kq            
     */
    public function setKq($kq)
    {
        $this->kq = $kq;
    }

    /**
     *
     * @param string $messenger            
     */
    public function setMessenger($messenger)
    {
        if($this->kq==false)
        {
            $this->messenger = '<span style="color=red;">'.$messenger.'</span>';
        }
        else if($this->kq==true) {
            $this->messenger = '<span style="color=green;">'.$messenger.'</span>';
        }
        
    }
}

?>