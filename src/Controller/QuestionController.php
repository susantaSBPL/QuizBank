<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Client\ClientQuestionType;
use App\Client\ClientQuestionCategory;
use App\Services\QuestionService;
use App\Entity\WeblogAction;
use Exception;

class QuestionController extends AbstractController
{
    /**
     * @Route("/api/addQuestionType", name="api_add_question_type", methods={"POST"})
     *
     * @param Request         $request
     * @param QuestionService $questionService
     *
     * @return JsonResponse
     */
    public function addQuestionTypeAction(Request $request, QuestionService $questionService)
    {
        $data     = json_decode($request->getContent(), true);
        $errorMsg = '';

        try {
            $questionType = $questionService->addQuestionType($data['type'], WeblogAction::CREATE_NEW_QUESTION_TYPE);
            $clientQuestionType = new ClientQuestionType($questionType);
            return new JsonResponse(['questionType' => $clientQuestionType], 200);

        } catch(Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return new JsonResponse(['error' => $errorMsg], 400);
    }

    /**
     * @Route("/api/getQuestionTypes", name="api_get_question_types", methods={"GET"})
     *
     * @param QuestionService $questionService
     *
     * @return JsonResponse
     */
    public function getQuestionTypesAction(QuestionService $questionService)
    {
        $errorMsg = '';

        try {
            $questionTypes = $questionService->getActiveQuestionTypes();

            return new JsonResponse(['questionTypes' => $questionTypes], 200);

        } catch(Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return new JsonResponse(['error' => $errorMsg], 400);
    }

    /**
     * @Route("/api/addQuestionCategory", name="api_add_question_category", methods={"POST"})
     *
     * @param Request         $request
     * @param QuestionService $questionService
     *
     * @return JsonResponse
     */
    public function addQuestionCategoryAction(Request $request, QuestionService $questionService)
    {
        $data     = json_decode($request->getContent(), true);
        $errorMsg = '';

        try {
            $questionCategory = $questionService->addQuestionCategory($data['category'], WeblogAction::CREATE_NEW_QUESTION_CATEGORY);
            $clientQuestionCategory = new ClientQuestionCategory($questionCategory);
            return new JsonResponse(['questionCategory' => $clientQuestionCategory], 200);

        } catch(Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return new JsonResponse(['error' => $errorMsg], 400);
    }

    /**
     * @Route("/api/getQuestionCategories", name="api_get_question_categories", methods={"GET"})
     *
     * @param QuestionService $questionService
     *
     * @return JsonResponse
     */
    public function getQuestionCategoriesAction(QuestionService $questionService)
    {
        $errorMsg = '';

        try {
            $questionCategories = $questionService->getActiveQuestionCategories();

            return new JsonResponse(['questionCategories' => $questionCategories], 200);

        } catch(Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return new JsonResponse(['error' => $errorMsg], 400);
    }

    /**
     * @Route("/api/getQuestionRequirements", name="api_get_question_requirements", methods={"GET"})
     *
     * @param QuestionService $questionService
     *
     * @return JsonResponse
     */
    public function getQuestionRequirementsAction(QuestionService $questionService)
    {
        $errorMsg = '';

        try {
            $questionRequirements = $questionService->getQuestionCategoriesTypes();

            return new JsonResponse(['questionRequirements' => $questionRequirements], 200);

        } catch(Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return new JsonResponse(['error' => $errorMsg], 400);
    }

    /**
     * @Route("/api/addQuestion", name="api_add_question", methods={"POST"})
     *
     * @param Request         $request
     * @param QuestionService $questionService
     *
     * @return JsonResponse
     */
    public function addQuestionAction(Request $request, QuestionService $questionService)
    {
        $data     = json_decode($request->getContent(), true);
        $errorMsg = '';

        try {
            $questionService->addQuestion($data, WeblogAction::CREATE_NEW_QUESTION);

            return new JsonResponse(['success' => true], 200);

        } catch(Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return new JsonResponse(['error' => $errorMsg], 400);
    }

    /**
     * @Route("/api/addQuestionFile", name="api_add_question_file", methods={"POST"})
     *
     * @param Request         $request
     * @param QuestionService $questionService
     *
     * @return JsonResponse
     */
    public function addQuestionFileAction(Request $request, QuestionService $questionService)
    {
        $questionsFile = $request->files->get('file');
        $errorMsg = '';

        try {
            $questionService->addQuestion($data, WeblogAction::CREATE_NEW_QUESTION);

            return new JsonResponse(['success' => true], 200);

        } catch(Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return new JsonResponse(['error' => $errorMsg], 400);
    }
}