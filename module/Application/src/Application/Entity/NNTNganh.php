<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="nntnganh",
 *     indexes={
 *         @ORM\Index(name="fk_nguoinopthue_has_nganh_nganh1_idx", columns={"MaNganh"}),
 *         @ORM\Index(name="fk_nguoinopthue_has_nganh_nguoinopthue1_idx", columns={"MaSoThue"})
 *     }
 * )
 */
class NNTNganh
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $IDNNTNganh;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiGianBatDau;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $ThoiGianKetThuc;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="NNTNganhs")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nganh", inversedBy="NNTNganhs")
     * @ORM\JoinColumn(name="MaNganh", referencedColumnName="MaNganh", nullable=false, onDelete="restrict")
     */
    private $nganh;
 /**
     * @return the $IDNNTNganh
     */
    public function getIDNNTNganh()
    {
        return $this->IDNNTNganh;
    }

 /**
     * @return the $ThoiGianBatDau
     */
    public function getThoiGianBatDau()
    {
        return $this->ThoiGianBatDau!=null ?$this->ThoiGianBatDau->format('d-m-Y'):null;
    }

    /** string datetime d-m-Y
     * @return string
     */
    public function getThoiGianKetThuc()
    {
        return $this->ThoiGianKetThuc!=null ?$this->ThoiGianKetThuc->format('d-m-Y'):null;
        
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @return nganh
     */
    public function getNganh()
    {
        return $this->nganh;
    }

 /**
     * @param field_type $IDNNTNganh
     */
    public function setIDNNTNganh($IDNNTNganh)
    {
        $this->IDNNTNganh = $IDNNTNganh;
    }

 /**    param: string format Y-m-d
     * @param field_type $ThoiGianBatDau
     */
    public function setThoiGianBatDau($ThoiGianBatDau)
    {
        if($ThoiGianBatDau!=null){
            $date = \DateTime::createFromFormat("Y-m-d", $ThoiGianBatDau);
            $this->ThoiGianBatDau = $date;
        }
        else{
            $this->ThoiGianBatDau = $ThoiGianBatDau;
        }
        
    }

 /**
  *     param: format Y-m-d
     * @param string $ThoiGianKetThuc
     */
    public function setThoiGianKetThuc($ThoiGianKetThuc)
    {
        
       
        
        if($ThoiGianKetThuc!=null){
             $date = \DateTime::createFromFormat("Y-m-d", $ThoiGianKetThuc);
            $this->ThoiGianKetThuc = $date;
        }
        else{
            $this->ThoiGianKetThuc = $ThoiGianKetThuc;
        }
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

 /**
     * @param field_type $nganh
     */
    public function setNganh($nganh)
    {
        $this->nganh = $nganh;
    }

}