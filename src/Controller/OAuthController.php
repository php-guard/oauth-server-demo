<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 29/04/2018
 * Time: 18:48
 */

namespace App\Controller;


use App\Entity\User;
use App\OAuth\Roles\ResourceOwner;
use App\Services\OAuth;
use Doctrine\ORM\EntityManagerInterface;
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
        $authorizationEndpoint = $this->oauth->getServer()->getAuthorizationEndpoint();

        /**
         * @var User $user
         */
        if ($request->getMethod() == 'POST' && $user = $this->getUser()) {
            if ($response = $authorizationEndpoint->verifyRequest($requestPsr)) {
                return $response;
            }
            $client = $authorizationEndpoint->getClient();

            $authorization = $request->request->get('authorization');
            $resourceOwner = $authorizationEndpoint->getResourceOwner();
            if (!$resourceOwner instanceof ResourceOwner) {
                throw new \LogicException();
            }
            if ($authorization == 'allow') {
                $resourceOwner->setDecision($client, $authorizationEndpoint->getScopes(), true);
            } else if ($authorization == 'deny') {
                $resourceOwner->setDecision($client, []);
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
        return $this->oauth->getServer()->getTokenEndpoint()->handleRequest($request);
    }
}