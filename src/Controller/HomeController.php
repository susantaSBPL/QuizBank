<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\UserService;
use App\Entity\UserFormData;
use App\Entity\WeblogAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Exception;

class HomeController extends AbstractController
{
    /**
     * @Route("/{reactRouting}", name="home", defaults={"reactRouting": null})
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/api/home", name="api_home")
     */
    public function home()
    {
        return new JsonResponse(['result' => true], 200);
    }

    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     *
     * @param Request     $request
     * @param UserService $userService
     *
     * @return JsonResponse
     */
    public function registerAction(Request $request, UserService $userService)
    {
        $errors = [];
        $data   = json_decode($request->getContent(), true);

        try {
            $userFormData = new UserFormData();
            $userFormData->setFirstName($data['first_name']);
            $userFormData->setLastName($data['last_name']);
            $userFormData->setEmail($data['email']);
            $userFormData->setPassword($data['password']);

            $user = $userService->addUser($userFormData, WeblogAction::CREATE_NEW_USER);

            return new JsonResponse(['user' => $user], 200);

        } catch(Exception $e) {
            $errors[] = $e->getMessage();
        }

        return new JsonResponse(['errors' => $errors], 400);
    }

    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function login()
    {
        return new JsonResponse(['result' => true], 200);
    }

    /**
     * @Route("/api/profile", name="api_profile")
     *
     * @return JsonResponse
     */
    public function profile()
    {
        return $this->json(['user' => $this->getUser()],200, [], ['groups' => ['api']]);
    }
}