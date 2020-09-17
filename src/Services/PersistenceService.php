<?php declare(strict_types=1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Services;

use App\Entity\AbstractQuizBankEntity;
use App\Entity\User;
use App\Entity\Weblog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Exception;

/**
 * Class PersistenceService
 */
class PersistenceService extends BaseService
{
    const PERSIST = 'PERSIST';
    const REMOVE  = 'REMOVE';

    private DateService           $dateService;
    private SessionInterface      $session;
    private TokenStorageInterface $tokenStorage;

    /**
     * PersistenceService constructor.
     * @param EntityManagerInterface $em
     * @param DateService            $dateService
     * @param SessionInterface       $session
     * @param TokenStorageInterface  $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, DateService $dateService, SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($em);
        $this->dateService  = $dateService;
        $this->session      = $session;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param AbstractQuizBankEntity $obj    - the object to persist
     * @param string                 $action - the Weblog action involved
     *
     * @throws Exception
     */
    public function persistEntity(AbstractQuizBankEntity $obj, string $action)
    {
        $this->persistEntities(array($obj), $action);
    }

    /**
     * @param array  $objArray
     * @param string $action
     *
     * @throws Exception
     */
    public function persistEntities(array $objArray, string $action)
    {
        $this->doPersist($objArray, self::PERSIST, $action);
    }

    /**
     * @param AbstractQuizBankEntity $obj
     * @param string                 $action
     *
     * @throws Exception
     */
    public function removeEntity(AbstractQuizBankEntity $obj, string $action)
    {
        $this->removeEntities(array($obj), $action);
    }

    /**
     * @param array  $objArray
     * @param string $action
     *
     * @throws Exception
     */
    public function removeEntities(array $objArray, string $action)
    {
        $this->doPersist($objArray, self::REMOVE, $action);
    }

    /**
     * Persist an array of $objects
     *
     * @param array|AbstractQuizBankEntity[] $objects - array of entities to persist
     * @param string                         $type    - PERSIST or REMOVE
     * @param string                         $action  - Weblog action involved
     *
     * @throws Exception
     */
    private function doPersist(array $objects, string $type, string $action)
    {
        foreach ($objects as $obj) :
            switch ($type) {
                case self::PERSIST:
                    $this->em->persist($obj);
                    break;
                case self::REMOVE:
                    $this->em->remove($obj);
                    break;
            }
        endforeach;

        $this->em->flush();

        foreach ($objects as $obj) :
            $this->auditObject($obj, $action);
        endforeach;
    }

    /**
     * @param AbstractQuizBankEntity $obj
     * @param string                 $action
     *
     * @throws Exception
     */
    private function auditObject(AbstractQuizBankEntity $obj, string $action)
    {
        $this->audit($obj->toString(), $action);
    }

    /**
     * @param string $args
     * @param string $action
     *
     * @throws Exception
     */
    private function audit(string $args, string $action)
    {
        $request = Request::createFromGlobals();
        $weblog = new Weblog();

        $id = -1;
        $role = '-1';
        $authToken = $this->tokenStorage->getToken();
        if ($authToken instanceof PostAuthenticationGuardToken) {
            $user = $authToken->getUser();
            if ($user instanceof User) {
                $id = $user->getId();
                $role = implode($user->getRoles());
            }
        }

        $weblog->setDatestamp($this->dateService->getServerDateTime());
        $weblog->setUserId($id);
        $weblog->setRole($role);

        $ips = $request->getClientIps();
        $weblog->setIp($ips[count($ips) - 1]);

        $weblog->setSessionId($this->session->getId());
        $weblog->setPage($request->getRequestUri());
        $weblog->setPostString($args);
        $weblog->setAction($action);

        $this->em->persist($weblog);
        $this->em->flush();
    }
}
