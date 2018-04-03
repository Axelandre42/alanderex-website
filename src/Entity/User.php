<?php
// src/Entity/User.php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=63)
     */
    protected $displayName;
    
    /**
     * @ORM\OneToOne(targetEntity="Telegram", mappedBy="user")
     */
    protected $telegram;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    public function setDisplayName(string $displayName)
    {
        $this->displayName = $displayName;
    }
    
    public function getDisplayName()
    {
        return $this->displayName;
    }
    
    public function setTelegram(Telegram $telegram)
    {
        $this->telegram = $telegram;
    }
    
    public function getTelegram()
    {
        return $this->telegram;
    }
}