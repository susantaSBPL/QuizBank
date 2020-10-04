<?php declare(strict_types=1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserVerificationUrlRepository")
 */
class UserVerificationUrl extends AbstractQuizBankEntity
{
    const USER_VERIFICATION_URL_TYPE_REGISTER        = 'register';
    const USER_VERIFICATION_URL_TYPE_FORGOT_PASSWORD = 'forgot_password';
    const USER_VERIFICATION_URL_TYPE_FORGOT_USERNAME = 'forgot_username';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var User
     */
    private User $user;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private string $verificationType;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private string $verificationKey;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    private DateTime $expiredDateTime;

    /**
     * UserVerificationUrl constructor.
     *
     * @param User     $user
     * @param string   $verificationType
     * @param string   $verificationKey
     * @param DateTime $expiredDateTime
     */
    public function __construct(User $user, string $verificationType, string $verificationKey, DateTime $expiredDateTime)
    {
        $this->user             = $user;
        $this->verificationType = $verificationType;
        $this->verificationKey  = $verificationKey;
        $this->expiredDateTime  = $expiredDateTime;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return DateTime
     */
    public function getExpiredDateTime(): DateTime
    {
        return $this->expiredDateTime;
    }

    /**
     * @return string
     */
    public function getVerificationKey(): string
    {
        return $this->verificationKey;
    }

    /**
     * @return string
     */
    public function getVerificationType(): string
    {
        return $this->verificationType;
    }

    /**
     * Represent the Entity as a string.
     *
     * @return string
     */
    public function toAuditString(): string
    {
        // Represent the Entity as a string $s
        $s = "UserVerificationUrl {";
        $s = $s."}";

        return $s;
    }
}
