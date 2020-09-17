<?php declare(strict_types=1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeblogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Weblog
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", length=36, name="user_id", nullable=false)
     */
    private int $userId;

    /**
     * @ORM\Column(type="string", name="user_type", nullable=false)
     */
    private string $role;

    /**
     * @ORM\Column(type="string", name="session_id", length=50, nullable=false)
     */
    private string $sessionId;

    /**
     * @ORM\Column(type="string", length=3000, nullable=false)
     */
    private string $page;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private string $action;

    /**
     * @ORM\Column(type="string", name="post_string", length=2000, nullable=false)
     */
    private string $postString;

    /**
     * @ORM\Column(type="string", name="ip_address", nullable=true)
     */
    private ?string $ip = null;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $datestamp;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     */
    public function setSessionId(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage(string $page)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getPostString()
    {
        return $this->postString;
    }

    /**
     * @param string $postString
     */
    public function setPostString(string $postString)
    {
        $this->postString = $postString;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * MAS: Can be null if we are auditing a command action
     * @param string|null $ip
     */
    public function setIp(?string $ip)
    {
        $this->ip = $ip ? $ip : null;
    }

    /**
     * @return DateTime
     */
    public function getDatestamp()
    {
        return $this->datestamp;
    }

    /**
     * @param DateTime $datestamp
     */
    public function setDatestamp(DateTime $datestamp): void
    {
        $this->datestamp = $datestamp;
    }
}
