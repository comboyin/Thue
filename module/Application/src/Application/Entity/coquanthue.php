<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="coquanthue"),
 * indexes={@ORM\Index(name="fk_coquanthue_coquanthue1_idx", columns={"MaChiCuc"})}
 */
class coquanthue
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    private $MaCoQuan;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $TenGoi;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\coquanthue", mappedBy="chicucthue")
     **/
    private $doithue;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\coquanthue", inversedBy="doithue")
     * @ORM\JoinColumn(name="MaChiCuc", referencedColumnName="MaCoQuan")
     **/
    private $chicucthue;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\phuong", mappedBy="coquanthue")
     */
    private $phuongs;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\user", mappedBy="coquanthue")
     */
    private $users;
 /**
     * @return the $MaCoQuan
     */
    public function getMaCoQuan()
    {
        return $this->MaCoQuan;
    }

 /**
     * @return the $TenGoi
     */
    public function getTenGoi()
    {
        return $this->TenGoi;
    }

 /**
     * @return coquanthue
     */
    public function getDoithue()
    {
        return $this->doithue;
    }

 /**
     * @return coquanthue
     */
    public function getChicucthue()
    {
        return $this->chicucthue;
    }

 /**
     * @return the $phuongs
     */
    public function getPhuongs()
    {
        return $this->phuongs;
    }

 /**
     * @return user[]|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
    /**
     * Trả về đội trưởng của 1 đội thuế  */
    public function getDoiTruong(){
        if($this->getDoithue()==null){
            foreach ($this->getUsers() as $user){
                if($user->getLoaiUser()==3){
                    return $user;
                }
            }
            
        }
        return null;
    }

 /**
     * @param field_type $MaCoQuan
     */
    public function setMaCoQuan($MaCoQuan)
    {
        $this->MaCoQuan = $MaCoQuan;
    }

 /**
     * @param field_type $TenGoi
     */
    public function setTenGoi($TenGoi)
    {
        $this->TenGoi = $TenGoi;
    }

 /**
     * @param field_type $doithue
     */
    public function setDoithue($doithue)
    {
        $this->doithue = $doithue;
    }

 /**
     * @param field_type $chicucthue
     */
    public function setChicucthue($chicucthue)
    {
        $this->chicucthue = $chicucthue;
    }

 /**
     * @param field_type $phuongs
     */
    public function setPhuongs($phuongs)
    {
        $this->phuongs = $phuongs;
    }

 /**
     * @param field_type $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }



}