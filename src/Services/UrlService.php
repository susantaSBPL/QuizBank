<?php declare (strict_types = 1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Services;

use App\Entity\UserVerificationUrl;
use App\Entity\WeblogAction;
use App\Repository\UserVerificationUrlRepository;
use App\Services\UrlInfo;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Exception;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class UrlService
 */
class UrlService extends BaseService
{
    private DateService                   $dateService;
    private PersistenceService            $persistenceService;
    private UserVerificationUrlRepository $userVerificationUrlRepository;

    /**
     * UrlService constructor.
     *
     * @param EntityManagerInterface        $em
     * @param DateService                   $dateService
     * @param PersistenceService            $persistenceService
     * @param UserVerificationUrlRepository $userVerificationUrlRepository
     */
    public function __construct(EntityManagerInterface $em, DateService $dateService, PersistenceService $persistenceService, UserVerificationUrlRepository $userVerificationUrlRepository)
    {
        parent::__construct($em);

        $this->dateService                   = $dateService;
        $this->persistenceService            = $persistenceService;
        $this->userVerificationUrlRepository = $userVerificationUrlRepository;
    }

    /**
     * @param User   $user
     * @param string $verificationType
     *
     * @return UrlInfo
     *
     * @throws Exception
     */
    public function createUserVerificationUrl(User $user, string $verificationType)
    {
        // Cleanup "old" link if they exist
        $this->deleteUserVerificationUrlByUser($user, $verificationType);

        switch ($verificationType) {
            case UserVerificationUrl::USER_VERIFICATION_URL_TYPE_FORGOT_PASSWORD:
                // Link is valid for 30 minutes
                $interval = new DateInterval("PT30M");
                $intervalString = '30 minutes.';
                break;

            case UserVerificationUrl::USER_VERIFICATION_URL_TYPE_FORGOT_USERNAME:
                // Link is valid for 12 hours
                $interval = new DateInterval("PT12H");
                $intervalString = '12 hours.';
                break;

            case UserVerificationUrl::USER_VERIFICATION_URL_TYPE_REGISTER:
                // Link is valid for 14 days
                $interval = new DateInterval("P14D");
                $intervalString = '14 days.';
                break;

            default:
                // MAS TODO Log
                throw new Exception('Invalid Link Type');
        }

        $expires = $this->dateService->getServerDateTime();
        $expires->add($interval);

        $verificationKey = md5(uniqid($user->getEmail(), true));
        $userVerificationUrl = new UserVerificationUrl($user, $verificationType, $verificationKey, $expires);

        $this->persistenceService->persistEntity($userVerificationUrl, WeblogAction::USER_CREATE_VERIFICATION_URL);

        return new UrlInfo($verificationKey, $intervalString);
    }

    /**
     * @param string $verificationKey
     * @param string $verificationType
     *
     * @return UserVerificationUrl|null
     *
     * @throws Exception
     */
    public function getUserVerificationUrl(string $verificationKey, string $verificationType)
    {
        $userVerificationUrl = $this->findUserVerificationUrl($verificationKey, $verificationType);

        if (null === $userVerificationUrl) {
            throw new AuthenticationException('Invalid Link');
        }

        // Get Link data
        $expired = $userVerificationUrl->getExpiredDateTime();

        // Test Interval
        $now = $this->dateService->getServerDateTime();
        if ($now > $expired) {
            // Delete the expired link
            $this->deleteUserVerificationUrl($userVerificationUrl);

            throw new AuthenticationException('Expired Link');
        }

        return $userVerificationUrl;
    }

    /**
     * @param string $verificationKey
     * @param string $verificationType
     * @param bool   $bDeleteLink
     *
     * @return User
     *
     * @throws Exception
     */
    public function getUserByVerificationKey(string $verificationKey, string $verificationType, bool $bDeleteLink = false)
    {
        $userVerificationUrl = $this->getUserVerificationUrl($verificationKey, $verificationType);
        $user = $userVerificationUrl->getUser();

        if ($bDeleteLink) {
            $this->deleteUserVerificationUrl($userVerificationUrl);
        }

        return $user;
    }

    /**
     * Deletes the Url link if it exists
     *
     * @param User   $user
     * @param string $verificationType
     *
     * @throws Exception
     */
    public function deleteUserVerificationUrlByUser(User $user, string $verificationType)
    {
        // MAS TODO Code for > 1 even though should not happen
        $userVerificationUrl = $this->userVerificationUrlRepository->getUserVerificationUrlByUserByType($user, $verificationType);

        if ($userVerificationUrl) {
            $this->deleteUserVerificationUrl($userVerificationUrl);
        }
    }

    /**
     * Deletes the Url link if it exists
     *
     * @param string $verificationKey
     * @param string $verificationType
     *
     * @throws Exception
     */
    public function deleteUserVerificationUrlByKey(string $verificationKey, string $verificationType)
    {
        $userVerificationUrl = $this->findUserVerificationUrl($verificationKey, $verificationType);

        if ($userVerificationUrl) {
            $this->deleteUserVerificationUrl($userVerificationUrl);
        }
    }

    /**
     * @param UserVerificationUrl $userVerificationUrl
     *
     * @throws Exception
     */
    public function deleteUserVerificationUrl(UserVerificationUrl $userVerificationUrl)
    {
        $this->persistenceService->removeEntity($userVerificationUrl, WeblogAction::USER_DELETE_VERIFICATION_URL);
    }

    /**
     * @param string $verificationKey
     * @param string $verificationType
     *
     * @return null|object|UserVerificationUrl
     */
    private function findUserVerificationUrl(string $verificationKey, string $verificationType)
    {
        return $this->userVerificationUrlRepository->getUserVerificationUrlByKeyByType($verificationKey, $verificationType);
    }
}
