<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="telegram")
 */
class Telegram
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="telegram")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $chatId;
    
    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    protected $validationToken;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $validationTimeToLive;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $validationIssued;
    
    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    protected $username;
    
    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    protected $displayName;
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
    }
    
    public function getChatId()
    {
        return $this->chatId;
    }
    
    public function setValidationToken(string $validationToken)
    {
        $this->validationToken = $validationToken;
    }
    
    public function getValidationToken()
    {
        return $this->validationToken;
    }
    
    public function setValidationTimeToLive($validationTimeToLive)
    {
        $this->validationTimeToLive = $validationTimeToLive;
    }
    
    public function getValidationTimeToLive()
    {
        return $this->validationTimeToLive;
    }
    
    public function setValidationIssued($validationIssued)
    {
        $this->validationIssued = $validationIssued;
    }
    
    public function getValidationIssued()
    {
        return $this->validationIssued;
    }
    
    public function setUsername(string $username)
    {
        $this->username = $username;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setDisplayName(string $displayName)
    {
        $this->displayName = $displayName;
    }
    
    public function getDisplayName()
    {
        return $this->displayName;
    }
    
    public function __toString()
    {
        return $this->displayName;
    }
}