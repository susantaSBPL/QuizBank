<?php

namespace App\DataFixtures;

use App\Entity\QuestionCategory;
use App\Entity\QuestionType;
use App\Entity\User;
use App\Entity\UserFormData;
use App\Services\UserService;
use App\Entity\WeblogAction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class AppFixture extends Fixture
{
    private ObjectManager $manager;

    /* Start Question Types */
    const MULTIPLE_CHOICE = 1;
    const BOOLEAN         = 2;

    const QUESTION_TYPES = [
        ['id' => self::MULTIPLE_CHOICE,  'type' => 'Multiple Choice'],
        ['id' => self::BOOLEAN,          'type' => 'True/False'],
    ];
    /* End Question Type */

    /* Start Question Category */
    const GENERAL_KNOWLEDGE = 1;
    const ENTERTAINMENT     = 2;
    const SCIENCE           = 3;
    const MYTHOLOGY         = 4;
    const SPORTS            = 5;
    const GEOGRAPHY         = 6;
    const HISTORY           = 7;
    const POLITICS          = 8;
    const ART               = 9;
    const CELEBRITIES       = 10;
    const ANIMALS           = 11;
    const VEHICLES          = 12;
    const GADGETS           = 13;

    const QUESTION_CATEGORIES = [
        ['id' => self::GENERAL_KNOWLEDGE,  'category' => 'General Knowledge'],
        ['id' => self::ENTERTAINMENT,      'category' => 'Entertainment'],
        ['id' => self::SCIENCE,            'category' => 'Science'],
        ['id' => self::MYTHOLOGY,          'category' => 'Mythology'],
        ['id' => self::SPORTS,             'category' => 'Sports'],
        ['id' => self::GEOGRAPHY,          'category' => 'Geography'],
        ['id' => self::HISTORY,            'category' => 'History'],
        ['id' => self::POLITICS,           'category' => 'Politics'],
        ['id' => self::ART,                'category' => 'Art'],
        ['id' => self::CELEBRITIES,        'category' => 'Celebrities'],
        ['id' => self::ANIMALS,            'category' => 'Animals'],
        ['id' => self::VEHICLES,           'category' => 'Vehicles'],
        ['id' => self::GADGETS,            'category' => 'Gadgets'],
    ];
    /* End Question Category */

    /* Start User */
    private $users = [
        [
            'firstName' => 'Susanta Kumar',
            'lastName'  => 'Sahoo',
            'email'     => 'susantamcacvrca@gmail.com',
            'password'  => 'Abcd1234',
            'role'      => User::USER_ROLE_ADMIN
        ],
    ];

    private UserService $userService;

    /**
     * AppFixture constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param ObjectManager $manager
     *
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadData($manager);
    }

    /**
     * @param ObjectManager $manager
     *
     * @throws Exception
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(count(self::QUESTION_TYPES), function (int $idx) {
            $questionType = self::QUESTION_TYPES[$idx];

            return [
                new QuestionType($questionType['type']),
                self::getReferenceName(QuestionType::class, $questionType['id']),
            ];
        });

        $this->createMany(count(self::QUESTION_CATEGORIES), function (int $idx) {
            $questionCategory = self::QUESTION_CATEGORIES[$idx];

            return [
                new QuestionCategory($questionCategory['category']),
                self::getReferenceName(QuestionCategory::class, $questionCategory['id']),
            ];
        });

        foreach ($this->users as $user) {
            $userData = new UserFormData();
            $userData->setFirstName($user['firstName']);
            $userData->setLastName($user['lastName']);
            $userData->setEmail($user['email']);
            $userData->setPassword($user['password']);
            $userData->setRoles([$user['role']]);
            $userData->setIsActive(($user['role'] === User::USER_ROLE_ADMIN));

            $this->userService->addUser($userData, WeblogAction::CREATE_NEW_USER);
        }

        $manager->flush();
    }

    /**
     * @param int      $count
     * @param callable $factory
     */
    protected function createMany(int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            list($entity, $referenceName) = $factory($i);

            $this->manager->persist($entity);

            // If required have doctrine store so we can retrieve later
            if ($referenceName) {
                $this->addReference($referenceName, $entity);
            }
        }
    }

    /**
     * @param string     $className
     * @param string|int $id
     *
     * @return string
     */
    protected static function getReferenceName(string $className, $id): string
    {
        return  $className.'_'.$id;
    }
}
