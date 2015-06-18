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
    {}

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
        $this->messenger = $messenger;
    }
}

?>