<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 * name="chat",
 * indexes={
 * @ORM\Index(name="fk_chat_user1_idx", columns={"MaUser"})
 * }
 * )
 */
class chat
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $IDChat;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\user", inversedBy="chats")
     * @ORM\JoinColumn(name="MaUser", referencedColumnName="MaUser", nullable=false, onDelete="restrict")
     */
    private $user;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $Time;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $NoiDung;
 /**
     * @return the $IDChat
     */
    public function getIDChat()
    {
        return $this->IDChat;
    }

 /**
     * @return the $user
     */
    public function getUser()
    {
        return $this->user;
    }

 /**
     * @return the $Time
     */
    public function getTime()
    {
        return $this->Time->format('h:m:s - d/m/Y');
    }

 /**
     * @return the $NoiDung
     */
    public function getNoiDung()
    {
        return $this->NoiDung;
    }

 /**
     * @param field_type $IDChat
     */
    public function setIDChat($IDChat)
    {
        $this->IDChat = $IDChat;
    }

 /**
     * @param field_type $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

 /**
     * @param field_type $Time
     */
    public function setTime($Time)
    {
        $this->Time = \DateTime::createFromFormat('Y-m-d h:m:s', $Time);
    }

 /**
     * @param field_type $NoiDung
     */
    public function setNoiDung($NoiDung)
    {
        $this->NoiDung = $NoiDung;
    }

    
    
}