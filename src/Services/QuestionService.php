<?php declare (strict_types = 1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\QuestionType;
use App\Entity\QuestionCategory;
use App\Entity\Question;
use App\Entity\QuestionChoice;
use App\Repository\QuestionTypeRepository;
use App\Repository\QuestionCategoryRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuestionChoiceRepository;
use App\Client\ClientQuestionType;
use App\Client\ClientQuestionCategory;
use Exception;

/**
 * Class QuestionService
 */
class QuestionService extends BaseService
{
    private QuestionRepository         $questionRepository;
    private QuestionChoiceRepository   $questionChoiceRepository;
    private QuestionTypeRepository     $questionTypeRepository;
    private QuestionCategoryRepository $questionCategoryRepository;
    private PersistenceService         $persistenceService;
    private DateService                $dateService;

    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface     $em
     * @param QuestionRepository         $questionRepository
     * @param QuestionChoiceRepository   $questionChoiceRepository
     * @param QuestionTypeRepository     $questionTypeRepository
     * @param QuestionCategoryRepository $questionCategoryRepository
     * @param PersistenceService         $persistenceService
     * @param DateService                $dateService
     */
    public function __construct(EntityManagerInterface $em, QuestionRepository $questionRepository, QuestionChoiceRepository $questionChoiceRepository, QuestionTypeRepository $questionTypeRepository, QuestionCategoryRepository $questionCategoryRepository, PersistenceService $persistenceService, DateService $dateService)
    {
        parent::__construct($em);

        $this->questionRepository         = $questionRepository;
        $this->questionChoiceRepository   = $questionChoiceRepository;
        $this->questionTypeRepository     = $questionTypeRepository;
        $this->questionCategoryRepository = $questionCategoryRepository;
        $this->persistenceService         = $persistenceService;
        $this->dateService                = $dateService;
    }

    /**
     * @param string $questionTypeName
     * @param string $action
     *
     * @return QuestionType
     *
     * @throws Exception
     */
    public function addQuestionType(string $questionTypeName, string $action)
    {
        $questionType = new QuestionType($questionTypeName);

        $this->persistenceService->persistEntity($questionType, $action);

        return $questionType;
    }

    /**
     * @return ClientQuestionType[]|array
     */
    public function getActiveQuestionTypes()
    {
        $questionTypes       = $this->questionTypeRepository->findBy(['isActive' => true]);
        $clientQuestionTypes = [];
        foreach ($questionTypes as $questionType) {
            $clientQuestionTypes[] = new ClientQuestionType($questionType);
        }

        return $clientQuestionTypes;
    }

    /**
     * @param string $questionCategoryName
     * @param string $action
     *
     * @return QuestionCategory
     *
     * @throws Exception
     */
    public function addQuestionCategory(string $questionCategoryName, string $action)
    {
        $questionCategory = new QuestionCategory($questionCategoryName);

        $this->persistenceService->persistEntity($questionCategory, $action);

        return $questionCategory;
    }

    /**
     * @return ClientQuestionCategory[]|array
     */
    public function getActiveQuestionCategories()
    {
        $questionCategories       = $this->questionCategoryRepository->findBy(['isActive' => true]);
        $clientQuestionCategories = [];
        foreach ($questionCategories as $questionCategory) {
            $clientQuestionCategories[] = new ClientQuestionCategory($questionCategory);
        }

        return $clientQuestionCategories;
    }

    /**
     * @return array
     */
    public function getQuestionCategoriesTypes()
    {
        $requirements = [];
        $requirements['categories'] = $this->getActiveQuestionCategories();
        $requirements['types']      = $this->getActiveQuestionTypes();

        return $requirements;
    }

    /**
     * @param array  $questionDetails
     * @param string $action
     *
     * @throws Exception
     */
    public function addQuestion(array $questionDetails, string $action)
    {
        $questionData = [];
        foreach($questionDetails as $questionDetail) {
            $questionText  = $questionDetail['question'];
            $correctAnswer = $questionDetail['correctAnswer'];
            $queType       = $questionDetail['questionType'];
            $queCategory   = $questionDetail['questionCategory'];
            $difficulty    = $questionDetail['questionDifficulty'];

            $questionType = $this->questionTypeRepository->find($queType);
            if (!$questionType) {
                $questionType = $this->questionTypeRepository->findOneBy(['type' => $queType]);
            }
            $questionCategory = $this->questionCategoryRepository->find($queCategory);
            if (!$questionCategory) {
                $questionCategory = $this->questionCategoryRepository->findOneBy(['category' => $queCategory]);
            }

            if (!$questionType || !$questionCategory) {
                continue;
            }

            $question = new Question();
            $question->setQuestionType($questionType)
                ->setQuestionCategory($questionCategory)
                ->setDifficulty($difficulty)
                ->setQuestionText($questionText)
                ->setCreatedAt($this->dateService->getServerDateTime())
                ->setIsActive(true);

            array_push($questionData, $question);

            for ($i = 1; $i <= 4; $i++) {
                $questionChoice = new QuestionChoice();
                $questionChoice->setQuestion($question)
                    ->setChoiceText($questionDetail['answer'.$i])
                    ->setIsRightChoice($correctAnswer == $i)
                    ->setIsActive(true);

                array_push($questionData, $questionChoice);
            }
        }

        $this->persistenceService->persistEntities($questionData, $action);
    }
}
