<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 29/04/2018
 * Time: 18:48
 */

namespace App\Controller;


use App\Entity\User;
use App\OAuth\Roles\AuthorizationServerEndUser;
use App\Services\OAuth;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Endpoints\AuthorizationRequest;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OAuthController extends AbstractController
{
    /**
     * @var OAuth
     */
    private $oauth;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(OAuth $oauth, EntityManagerInterface $entityManager)
    {
        $this->oauth = $oauth;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface
     * @Route("/oauth/authorize")
     */
    public function authorize(Request $request)
    {
        $requestPsr = (new DiactorosFactory())->createRequest($request);
        $authorizationEndpoint = $this->oauth->getAuthorizationServer()->getAuthorizationEndpoint();

        /**
         * @var User $user
         */
        if ($request->getMethod() == 'POST' && $user = $this->getUser()) {
            if ($response = $authorizationEndpoint->verifyRequest($requestPsr)) {
                return $response;
            }
            $authorizationRequest = $authorizationEndpoint->getAuthorizationRequest();
            if(!$authorizationRequest instanceof AuthorizationRequest) {
                throw new \LogicException();
            }

            $client = $authorizationRequest->getClient();
            $authorization = $request->request->get('authorization');
            $endUser = $authorizationEndpoint->getAuthorizationServerEndUser();
            if (!$endUser instanceof AuthorizationServerEndUser) {
                throw new \LogicException();
            }
            if ($authorization == 'allow') {
                $endUser->setDecision($client, $authorizationRequest->getScopes(), true);
            } else if ($authorization == 'deny') {
                $endUser->setDecision($client, []);
            }
        }

        return $authorizationEndpoint->handleRequest($requestPsr);
    }

    /**
     * @param Request $request
     * @return \Psr\Http\Message\ResponseInterface
     * @Route("/oauth/token")
     */
    public function token(Request $request)
    {
        $request = (new DiactorosFactory())->createRequest($request);
        return $this->oauth->getAuthorizationServer()->getTokenEndpoint()->handleRequest($request);
    }
}