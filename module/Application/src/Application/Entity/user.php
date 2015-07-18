<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="user",
 * indexes={
 *         @ORM\Index(name="fk_user_coquanthue1_idx", columns={"MaCoQuan"}),
 *         @ORM\Index(name="fk_user_user1_idx", columns={"parentId"})
 * },
 * uniqueConstraints={
 * @ORM\UniqueConstraint(name="Email", columns={"Email"})
 * }
 * )
 */
class user implements InputFilterAwareInterface
{

    protected $inputFilter = null;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=20)
     */
    private $MaUser;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\user", mappedBy="parentUser")
     **/
    private $childrenUser;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\user", inversedBy="$childrenUser")
     * @ORM\JoinColumn(name="parentId", referencedColumnName="MaUser")
     **/
    private $parentUser;

    /**
     * @ORM\Column(type="integer",nullable=false, options={"default":"4"})
     */
    private $LoaiUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $TenUser;

    /**
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $MatKhau;

    /**
     * @ORM\Column(type="integer",nullable=false, options={"default":"1"})
     */
    private $TrangThai;
    
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukientruythu", mappedBy="user")
     */
    private $dukientruythus;
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukienmb", mappedBy="user")
     */
    private $dukienmbs;
    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\dukienthue", mappedBy="user")
     */
    private $dukienthues;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\buttoannothue", mappedBy="user")
     */
    private $buttoannothues;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\usernnt", mappedBy="user")
     * @var ArrayCollection
     */
    private $usernnts;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\coquanthue", inversedBy="users")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="MaCoQuan", referencedColumnName="MaCoQuan", nullable=true, onDelete="restrict")
     * })
     */
    private $coquanthue;
    
    
    /**
     * 
     * @var ArrayCollection  */
    private $usernntDangHoatDong;

    /**
     * @return the $childrenUser
     */
    public function getChildrenUser()
    {
        return $this->childrenUser;
    }

 /**
     * @return the $parentUser
     */
    public function getParentUser()
    {
        return $this->parentUser;
    }

 /**
     * @param field_type $childrenUser
     */
    public function setChildrenUser($childrenUser)
    {
        $this->childrenUser = $childrenUser;
    }

 /**
     * @param field_type $parentUser
     */
    public function setParentUser($parentUser)
    {
        $this->parentUser = $parentUser;
    }

 /**
     * @return usernnt[]|ArrayCollection
     */
    public function getUsernntDangHoatDong()
    {

        $this->usernntDangHoatDong = new ArrayCollection();
        foreach ($this->getUsernnts() as $usernnt){
            if($usernnt->getThoiGianKetThuc()==null){
                $this->usernntDangHoatDong->add($usernnt);
            }
        }
        return $this->usernntDangHoatDong;
    }

 /**
     *
     * @return the $MaUser
     */
    public function getMaUser()
    {
        return $this->MaUser;
    }

    /**
     *
     * @return the $LoaiUser
     */
    public function getLoaiUser()
    {
        return $this->LoaiUser;
    }

    /**
     *
     * @return string
     */
    public function getTenUser()
    {
        return $this->TenUser;
    }

    /**
     *
     * @return the $Email
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     *
     * @return the $MatKhau
     */
    public function getMatKhau()
    {
        return $this->MatKhau;
    }

    /**
     *
     * @return the $TrangThai
     */
    public function getTrangThai()
    {
        return $this->TrangThai;
    }

    /**
     *
     * @return the $buttoannothues
     */
    public function getButtoannothues()
    {
        return $this->buttoannothues;
    }

    /**
     *
     * @return usernnt[]
     */
    public function getUsernnts()
    {
        return $this->usernnts;
    }
    


    /**
     *
     * @return coquanthue
     */
    public function getCoquanthue()
    {
        return $this->coquanthue;
    }

    /**
     *
     * @param field_type $MaUser            
     */
    public function setMaUser($MaUser)
    {
        $this->MaUser = $MaUser;
    }

    /**
     *
     * @param field_type $LoaiUser            
     */
    public function setLoaiUser($LoaiUser)
    {
        $this->LoaiUser = $LoaiUser;
    }

    /**
     *
     * @param field_type $TenUser            
     */
    public function setTenUser($TenUser)
    {
        $this->TenUser = $TenUser;
    }

    /**
     *
     * @param field_type $Email            
     */
    public function setEmail($Email)
    {
        $this->Email = $Email;
    }

    /**
     *
     * @param field_type $MatKhau            
     */
    public function setMatKhau($MatKhau)
    {
        $this->MatKhau = $MatKhau;
    }

    /**
     *
     * @param field_type $TrangThai            
     */
    public function setTrangThai($TrangThai)
    {
        $this->TrangThai = $TrangThai;
    }

    /**
     *
     * @param field_type $buttoannothues            
     */
    public function setButtoannothues($buttoannothues)
    {
        $this->buttoannothues = $buttoannothues;
    }

    /**
     *
     * @param field_type $usernnts            
     */
    public function setUsernnts($usernnts)
    {
        $this->usernnts = $usernnts;
    }

    /**
     *
     * @param field_type $coquanthue            
     */
    public function setCoquanthue($coquanthue)
    {
        $this->coquanthue = $coquanthue;
    }

    public function getChucVu()
    {
        switch ($this->LoaiUser) {
            case 1:
                return "Admin";
            case 2:
                return "Chi cục trưởng";
            case 3:
                return "Đội trưởng";
            case 4:
                return "Đội viên";
        }
    }

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            
            $inputFilter->add($factory->createInput([
                'name' => 'MaUser',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => '8',
                            'max' => '64'
                        )
                    )
                )
            ]));
            
            $inputFilter->add($factory->createInput([
                'name' => 'MatKhau',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => '6',
                            'max' => '12'
                        )
                    )
                )
            ]
            ));
            
            /*
             * $inputFilter->add($factory->createInput([
             * 'name' => 'remenber',
             * 'filters' => array(
             * array(
             * 'name' => 'StripTags'
             * ),
             * array(
             * 'name' => 'StringTrim'
             * )
             * ),
             * 'validators' => array()
             * ]));
             */
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }

    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {}
}