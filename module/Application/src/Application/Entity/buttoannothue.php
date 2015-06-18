<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="buttoannothue",
 * indexes={
 * @ORM\Index(name="fk_buttoannothue_sono1_idx", columns={"KyLapBo","KyThue","TieuMuc","MaSoThue"}),
 * @ORM\Index(name="fk_buttoannothue_user1_idx", columns={"MaUser"})
 * }
 * )
 */
class buttoannothue
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $IDbuttoannothue;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $STDieuChinh;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $ThoiDiemDC;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $LyDo;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\user", inversedBy="buttoannothues")
     * @ORM\JoinColumn(name="MaUser", referencedColumnName="MaUser", nullable=false, onDelete="restrict")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\sono", inversedBy="buttoannothues")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="KyLapBo", referencedColumnName="KyLapBo", nullable=false, onDelete="restrict"),
     * @ORM\JoinColumn(name="KyThue", referencedColumnName="KyThue", nullable=false, onDelete="restrict"),
     * @ORM\JoinColumn(name="TieuMuc", referencedColumnName="TieuMuc", nullable=false, onDelete="restrict"),
     * @ORM\JoinColumn(name="MaSoThue", referencedColumnName="MaSoThue", nullable=false, onDelete="restrict")
     * })
     */
    private $sono;
 /**
     * @return the $IDbuttoannothue
     */
    public function getIDbuttoannothue()
    {
        return $this->IDbuttoannothue;
    }

 /**
     * @return the $STDieuChinh
     */
    public function getSTDieuChinh()
    {
        return $this->STDieuChinh;
    }

 /**
     * @return the $ThoiDiemDC
     */
    public function getThoiDiemDC()
    {
        return $this->ThoiDiemDC;
    }

 /**
     * @return the $LyDo
     */
    public function getLyDo()
    {
        return $this->LyDo;
    }

 /**
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }

 /**
     * @return the $sono
     */
    public function getSono()
    {
        return $this->sono;
    }

 /**
     * @param field_type $IDbuttoannothue
     */
    public function setIDbuttoannothue($IDbuttoannothue)
    {
        $this->IDbuttoannothue = $IDbuttoannothue;
    }

 /**
     * @param field_type $STDieuChinh
     */
    public function setSTDieuChinh($STDieuChinh)
    {
        $this->STDieuChinh = $STDieuChinh;
    }

 /**
     * @param field_type $ThoiDiemDC
     */
    public function setThoiDiemDC($ThoiDiemDC)
    {
        $this->ThoiDiemDC = $ThoiDiemDC;
    }

 /**
     * @param field_type $LyDo
     */
    public function setLyDo($LyDo)
    {
        $this->LyDo = $LyDo;
    }

 /**
     * @param field_type $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

 /**
     * @param field_type $sono
     */
    public function setSono($sono)
    {
        $this->sono = $sono;
    }


}