<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="usernnt",
 *     indexes={
 *         @ORM\Index(name="fk_user_has_nguoinopthue_nguoinopthue1_idx", columns={"MaSoThue"}),
 *         @ORM\Index(name="fk_user_has_nguoinopthue_user1_idx", columns={"MaUser"})
 *     }
 * )
 */
class usernnt
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $IDUserNNT;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\user", inversedBy="usernnts")
     * @ORM\JoinColumn(name="MaUser", referencedColumnName="MaUser", nullable=false, onDelete="restrict")
    *@var user
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\nguoinopthue", inversedBy="usernnts")
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     */
    private $nguoinopthue;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiGianBatDau;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiGianKetThuc;
 /**
     * @return the $IDUserNNT
     */
    public function getIDUserNNT()
    {
        return $this->IDUserNNT;
    }

 /**
     * @return user
     */
    public function getUser()
    {
        return $this->user;
    }

 /**
     * @return the $nguoinopthue
     */
    public function getNguoinopthue()
    {
        return $this->nguoinopthue;
    }

 /**
     * @return the $ThoiGianBatDau
     */
    public function getThoiGianBatDau()
    {
        return $this->ThoiGianBatDau;
    }

 /**
     * @return the $ThoiGianKetThuc
     */
    public function getThoiGianKetThuc()
    {
        return $this->ThoiGianKetThuc;
    }

 /**
     * @param field_type $IDUserNNT
     */
    public function setIDUserNNT($IDUserNNT)
    {
        $this->IDUserNNT = $IDUserNNT;
    }

 /**
     * @param field_type $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

 /**
     * @param field_type $nguoinopthue
     */
    public function setNguoinopthue($nguoinopthue)
    {
        $this->nguoinopthue = $nguoinopthue;
    }

 /**
     * @param field_type $ThoiGianBatDau
     */
    public function setThoiGianBatDau($ThoiGianBatDau)
    {
        $this->ThoiGianBatDau = $ThoiGianBatDau;
    }

 /**
     * @param field_type $ThoiGianKetThuc
     */
    public function setThoiGianKetThuc($ThoiGianKetThuc)
    {
        $this->ThoiGianKetThuc = $ThoiGianKetThuc;
    }




}