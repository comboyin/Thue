<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="nganh")
 */
class nganh
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20)
     */
    private $MaNganh;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $TenNganh;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\NNTNganh", mappedBy="nganh")
     */
    private $NNTNganhs;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\bieuthuetyle", mappedBy="nganh")
     */
    private $bieuthuetyles;
    
 /**
     * @return the $MaNganh
     */
    public function getMaNganh()
    {
        return $this->MaNganh;
    }

 /**
     * @return the $TenNganh
     */
    public function getTenNganh()
    {
        return $this->TenNganh;
    }


 /**
     * @return the $NNTNganhs
     */
    public function getNNTNganhs()
    {
        return $this->NNTNganhs;
    }

 /**
     * @param field_type $MaNganh
     */
    public function setMaNganh($MaNganh)
    {
        $this->MaNganh = $MaNganh;
    }

 /**
     * @param field_type $TenNganh
     */
    public function setTenNganh($TenNganh)
    {
        $this->TenNganh = $TenNganh;
    }


 /**
     * @param field_type $NNTNganhs
     */
    public function setNNTNganhs($NNTNganhs)
    {
        $this->NNTNganhs = $NNTNganhs;
    }

 
}