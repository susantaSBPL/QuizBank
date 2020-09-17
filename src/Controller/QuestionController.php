<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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

            return new JsonResponse(['questionType' => $questionType], 200);

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

            return new JsonResponse(['questionType' => $questionCategory], 200);

        } catch(Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return new JsonResponse(['error' => $errorMsg], 400);
    }
}