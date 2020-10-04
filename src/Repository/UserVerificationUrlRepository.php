<?php declare(strict_types=1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Repository;

use App\Entity\User;
use App\Entity\UserVerificationUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UserVerificationUrlRepository
 *
 * @method UserVerificationUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserVerificationUrl|null findOneBy(array $criteria, array $orderBy = null)
 *
 * Do not use outside of this class.
 *
 * @method UserVerificationUrl[]    findAll()
 * @method UserVerificationUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserVerificationUrlRepository extends ServiceEntityRepository
{
    /**
     * PracticeUserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVerificationUrl::class);
    }

    /**
     * @param User   $user
     * @param string $verificationType
     *
     * @return UserVerificationUrl|null
     */
    public function findUserVerificationUrlByUserByPracticeByType(User $user, string $verificationType)
    {
        $userVerificationUrl = $this->getUserVerificationUrlByUserByType($user, $verificationType);

        // If we have a link check if the link belongs to this practice, link belongs to us if there is no practice (default)
        // or the Practice is not us
        if ($userVerificationUrl) {
            $userVerificationUrl = null;
        }

        return $userVerificationUrl;
    }

    /**
     * @param User   $user
     * @param string $verificationType
     *
     * @return null|UserVerificationUrl
     */
    public function getUserVerificationUrlByUserByType(User $user, string $verificationType)
    {
        return $this->findOneBy(
            [
                'user'             => $user,
                'verificationType' => $verificationType,
            ]
        );
    }

    /**
     * @param string $verificationKey
     * @param string $verificationType
     *
     * @return null|UserVerificationUrl
     */
    public function getUserVerificationUrlByKeyByType(string $verificationKey, string $verificationType)
    {
        return $this->findOneBy(
            [
                'verificationKey'  => $verificationKey,
                'verificationType' => $verificationType,
            ]
        );
    }
}
