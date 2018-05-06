<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:54
 */

namespace App\OAuth\Roles;


use App\Entity\Authorization;
use App\Entity\Client;
use App\Entity\User;
use App\EventListener\AuthenticationEventListener;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Extensions\OpenID\Endpoints\AuthorizationEndpoint;
use OAuth2\Extensions\OpenID\Roles\ResourceOwnerInterface;
use OAuth2\Roles\ClientInterface;
use OAuth2\Roles\Clients\RegisteredClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\VarDumper\VarDumper;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class ResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var \Twig_Environment
     */
    private $templating;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var array|null
     */
    private $allowedScopes = null;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(SessionInterface $session,
                                TokenStorageInterface $tokenStorage,
                                UrlGeneratorInterface $router,
                                \Twig_Environment $templating,
                                EntityManagerInterface $em,
                                AuthorizationCheckerInterface $authorizationChecker,
                                RequestStack $requestStack
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->router = $router;
        $this->templating = $templating;
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
        $this->requestStack = $requestStack;
    }

    public function getLastTimeActivelyAuthenticated(): ?\DateTime
    {
        return $this->session->get(AuthenticationEventListener::LAST_TIME_ACTIVELY_AUTHENTICATED);
    }

    /**
     * You should handle theses values for the prompt parameter : select_account
     * @param bool $accountSelectionRequired
     * @param null|string $loginHint
     * @return ResponseInterface
     */
    public function authenticate(bool $accountSelectionRequired = false, ?string $loginHint = null): ResponseInterface
    {
        $targetPath = $this->requestStack->getCurrentRequest()->getRequestUri();
        return new RedirectResponse($this->router->generate('login', ['_target_path' => $targetPath]));
    }

    /**
     * If prompt value is login, server should re-authenticate the user. This means that this predicate should return
     * false, then authenticate() will be called and if login is successful, server handle again the authorization request
     * but returning true in isAuthenticatedMethod. Use a CSRF token like to handle this.
     *
     * @param bool $alwaysAuthenticate
     * @return bool
     */
    public function isAuthenticated(bool $alwaysAuthenticate = false): bool
    {
        return $this->authorizationChecker->isGranted('ROLE_USER');
    }

    /**
     * Client authorization is denied if $allowedScopes is empty
     * @param ClientInterface $client
     * @param array $allowedScopes
     * @param bool|null $remember
     */
    public function setDecision(ClientInterface $client, array $allowedScopes, bool $remember = false)
    {
        $this->allowedScopes = $allowedScopes;

        if (empty($allowedScopes) || !$remember || !$client instanceof RegisteredClient) {
            return;
        }

        /**
         * @var User $user
         */
        $user = $this->tokenStorage->getToken()->getUser();
        $clientEntity = $this->em->getRepository(Client::class)->findOneBy(['identifier' => $client->getIdentifier()]);
        if (!$clientEntity) {
            throw new \RuntimeException();
        }

        $authorization = $this->em->getRepository(Authorization::class)->findOneBy([
            'resourceOwner' => $user,
            'client' => $clientEntity
        ]);

        if (!$authorization) {
            $authorization = new Authorization();
            $authorization->setResourceOwner($user);
            $authorization->setClient($clientEntity);
        }

        $scopes = array_unique(array_merge($authorization->getScopes(), $allowedScopes));
        $authorization->setScopes($scopes);
        $this->em->persist($authorization);
        $this->em->flush();
    }

    public function hasGivenConsent(ClientInterface $client, array $scopes, ?bool $alwaysPromptConsent = false): ?array
    {
        if (is_null($this->allowedScopes) && $client instanceof RegisteredClient) {
            /**
             * @var User $user
             */
            $user = $this->tokenStorage->getToken()->getUser();
            $clientEntity = $this->em->getRepository(Client::class)->findOneBy(['identifier' => $client->getIdentifier()]);
            if (!$clientEntity) {
                throw new \RuntimeException();
            }

            $authorization = $this->em->getRepository(Authorization::class)->findOneBy([
                'resourceOwner' => $user,
                'client' => $clientEntity
            ]);
//VarDumper::dump($authorization);
//VarDumper::dump(array_diff($scopes, $authorization->getScopes()));
//die;
            if ($authorization) {
                if (empty(array_diff($scopes, $authorization->getScopes()))) {
                    // TODO Config include previous granted scopes ?
                    // return $authorization->getScopes();

                    return $scopes;
                }
            }
        }
        return $this->allowedScopes;
    }

    public function isInteractionRequiredForConsent(AuthorizationEndpoint $authorizationEndpoint): bool
    {
        return true;
    }

    public function getIdentifier(): string
    {
        /**
         * @var User $user
         */
        $user = $this->tokenStorage->getToken()->getUser();
        return $user->getUsername();
    }

    public function obtainConsent(\OAuth2\Endpoints\AuthorizationEndpoint $authorizationEndpoint, array $requestData): ResponseInterface
    {
        return new HtmlResponse($this->templating->render('oauth/authorize.html.twig', ['data' => $requestData]));
    }

    public function getClaims(array $scopes): array
    {
        return $scopes;
    }


    public function getAuthenticationContextClassReference(): ?string
    {
        return '0';
    }

    /**
     * @return string[]|null
     */
    public function getAuthenticationMethodsReferences(): ?array
    {
        return null;
    }

}