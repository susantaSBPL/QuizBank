<?php declare (strict_types = 1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\UserFormData;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserService
 */
class UserService extends BaseService
{
    private UserRepository               $userRepository;
    private PersistenceService           $persistenceService;
    private DateService                  $dateService;
    private AppMailer                    $appMailer;
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface       $em
     * @param UserRepository               $userRepository
     * @param PersistenceService           $persistenceService
     * @param DateService                  $dateService
     * @param AppMailer                    $appMailer
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, PersistenceService $persistenceService, DateService $dateService, AppMailer $appMailer, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($em);

        $this->userRepository     = $userRepository;
        $this->persistenceService = $persistenceService;
        $this->dateService        = $dateService;
        $this->appMailer          = $appMailer;
        $this->passwordEncoder    = $passwordEncoder;
    }

    /**
     * @param UserFormData $userFormData
     * @param string       $action
     *
     * @return User
     *
     * @throws Exception
     */
    public function addUser(UserFormData $userFormData, string $action)
    {
        $user = new User();
        $user->setFirstName($userFormData->getFirstName())
             ->setLastName($userFormData->getLastName())
             ->setEmail($userFormData->getEmail())
             ->setRoles($userFormData->getRoles())
             ->setCreatedAt($this->dateService->getServerDateTime())
             ->setIsActive($userFormData->getIsActive());

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $userFormData->getPassword());
        $user->setPassword($encodedPassword);

        $this->persistenceService->persistEntity($user, $action);

        if (!$user->getIsActive()) {
            $this->appMailer->sendNewUserEmail($user);
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     *
     * @return User|null
     */
    public function getUserDetail(UserInterface $user)
    {
        return $this->userRepository->find($user->getId());
    }
}
