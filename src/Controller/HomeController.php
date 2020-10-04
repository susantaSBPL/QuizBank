<?php

namespace App\Controller;

use App\Client\ClientUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\UserService;
use App\Services\PersistenceService;
use App\Entity\UserFormData;
use App\Entity\User;
use App\Entity\WeblogAction;
use App\Repository\UserVerificationUrlRepository;
use Exception;

class HomeController extends AbstractController
{
    /**
     * @Route("/activateUser/{verificationKey}", name="activate")
     */
    public function activate()
    {
        return $this->render('home/index.html.twig');
    }

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
        $role   = $data['isMaster'] ? User::USER_ROLE_MASTER : User::USER_ROLE_USER;

        try {
            $userFormData = new UserFormData();
            $userFormData->setFirstName($data['first_name']);
            $userFormData->setLastName($data['last_name']);
            $userFormData->setEmail($data['email']);
            $userFormData->setPassword($data['password']);
            $userFormData->setRoles([$role]);

            $user = $userService->addUser($userFormData, WeblogAction::CREATE_NEW_USER);

            return new JsonResponse(['user' => $user], 200);

        } catch(Exception $e) {
            $errors[] = $e->getMessage();
        }

        return new JsonResponse(['errors' => $errors], 400);
    }

    /**
     * @Route("/api/activateUser/{verificationKey}", name="api_activate_user", methods={"POST"})
     *
     * @param Request                       $request
     * @param UserVerificationUrlRepository $userVerificationUrlRepository
     * @param PersistenceService            $persistenceService
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function activateUserAction(Request $request, UserVerificationUrlRepository $userVerificationUrlRepository, PersistenceService $persistenceService)
    {
        $verificationUrl  = $request->get('verificationKey');
        $userVerification = $userVerificationUrlRepository->findOneBy(['verificationKey' => $verificationUrl]);
        $error = "";
        try {
            if ($userVerification) {
                $user = $userVerification->getUser();
                $user->setIsActive(true);

                $persistenceService->persistEntity($user, WeblogAction::REGISTER_NEW_USER);
                $persistenceService->removeEntity($userVerification, WeblogAction::USER_DELETE_VERIFICATION_URL);

                return new JsonResponse(['registered' => true, 'message' => 'User Activated Successfully!'], 200);
            }
        } catch (Exception $ex) {
            $error = $ex->getMessage();
        }

        return new JsonResponse(['registered' => false, 'message' => $error], 200);
    }

    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function loginAction()
    {
        return new JsonResponse(['authenticated' => true], 200);
    }

    /**
     * @Route("/api/userLogout", name="api_user_logout", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function logoutAction()
    {
        $this->get('security.token_storage')->setToken(null);
        $this->get('session')->invalidate();

        return new JsonResponse(['unauthenticated' => true], 200);
    }

    /**
     * @Route("/api/profile", name="_get_profile")
     *
     * @param UserService $userService
     *
     * @return JsonResponse
     */
    public function profileAction(UserService $userService)
    {
        $user       = $userService->getUserDetail($this->getUser());
        $clientUser = new ClientUser($user);

        return new JsonResponse(['user' => $clientUser],200);
    }
}