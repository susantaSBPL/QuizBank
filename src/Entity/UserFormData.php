<?php
/*
 *
 */
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserFormData
 */
class UserFormData
{
    /**
     * @Assert\NotBlank()
     *
     * @var string $firstName
     */
    private string $firstName;

    /**
     * @Assert\Blank()
     *
     * @var string|null $lastName
     */
    private ?string $lastName;

    /**
     * @Assert\Email()
     *
     * @var string $email
     */
    private string $email;

    /**
     * @Assert\NotBlank()
     *
     * @var array $roles
     */
    private array $roles;

    /**
     * @Assert\Blank()
     *
     * @var string|null $username
     */
    private ?string $username;

    /**
     * @Assert\NotBlank()
     *
     * @var string $password
     */
    private string $password;

    /**
     * @var bool $isActive
     */
    private bool $isActive;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param null|string $lastName
     */
    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     */
    public function setUsername(?string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive = false)
    {
        $this->isActive = $isActive;
    }
}
