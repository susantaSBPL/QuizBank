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

/**
 * Class UserService
 */
class UserService extends BaseService
{
    private UserRepository               $userRepository;
    private PersistenceService           $persistenceService;
    private DateService                  $dateService;
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface       $em
     * @param UserRepository               $userRepository
     * @param PersistenceService           $persistenceService
     * @param DateService                  $dateService
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, PersistenceService $persistenceService, DateService $dateService, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($em);

        $this->userRepository     = $userRepository;
        $this->persistenceService = $persistenceService;
        $this->dateService        = $dateService;
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
             ->setRoles(['ROLE_ADMIN'])
             ->setCreatedAt($this->dateService->getServerDateTime());

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $userFormData->getPassword());
        $user->setPassword($encodedPassword);

        $this->persistenceService->persistEntity($user, $action);

        return $user;
    }
}
