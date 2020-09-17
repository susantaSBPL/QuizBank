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
        $questionType = new QuestionType();
        $questionType->setType($questionTypeName);

        $this->persistenceService->persistEntity($questionType, $action);

        return $questionType;
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
        $questionCategory = new QuestionCategory();
        $questionCategory->setCategory($questionCategoryName);

        $this->persistenceService->persistEntity($questionCategory, $action);

        return $questionCategory;
    }
}
