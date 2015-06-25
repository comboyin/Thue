<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phuong"),
 * indexes={@ORM\Index(name="fk_phuong_coquanthue1_idx", columns={"MaCoQuan"})}
 */
class phuong
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20)
     */
    private $MaPhuong;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $TenPhuong;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\thongtinnnt", mappedBy="phuong")
     */
    private $thongtinnnts;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\coquanthue", inversedBy="phuongs")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="MaCoQuan", referencedColumnName="MaCoQuan", nullable=false, onDelete="restrict")
     * })
     */
    private $coquanthue;
 /**
     * @return the $MaPhuong
     */
    public function getMaPhuong()
    {
        return $this->MaPhuong;
    }

 /**
     * @return the $TenPhuong
     */
    public function getTenPhuong()
    {
        return $this->TenPhuong;
    }

 /**
     * @return the $thongtinnnts
     */
    public function getThongtinnnts()
    {
        return $this->thongtinnnts;
    }

 /**
     * @return coquanthue
     */
    public function getCoquanthue()
    {
        return $this->coquanthue;
    }

 /**
     * @param field_type $MaPhuong
     */
    public function setMaPhuong($MaPhuong)
    {
        $this->MaPhuong = $MaPhuong;
    }

 /**
     * @param field_type $TenPhuong
     */
    public function setTenPhuong($TenPhuong)
    {
        $this->TenPhuong = $TenPhuong;
    }

 /**
     * @param field_type $thongtinnnts
     */
    public function setThongtinnnts($thongtinnnts)
    {
        $this->thongtinnnts = $thongtinnnts;
    }

 /**
     * @param field_type $coquanthue
     */
    public function setCoquanthue($coquanthue)
    {
        $this->coquanthue = $coquanthue;
    }


}