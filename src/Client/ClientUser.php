<?php


namespace App\Client;

use JsonSerializable;
use App\Entity\User;


class ClientUser implements JsonSerializable
{
    private User $user;

    /**
     * ClientQuestionType constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->user->getId();
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->user->getFirstName();
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->user->getLastName();
    }

    /**
     * @return bool
     */
    public function getEmail(): bool
    {
        return $this->user->getEmail();
    }

    /**
     * @return string
     */
    public function getUserRole(): string
    {
        $role = 'USER';
        if ($this->user->isAdmin()) {
            $role = 'ADMIN';
        } elseif ($this->user->isMaster()) {
            $role = 'MASTER';
        }

        return $role;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $a = [];

        $a['firstName'] = $this->getFirstName();
        $a['lastName']  = $this->getLastName();
        $a['role']      = $this->getUserRole();

        return $a;
    }
}