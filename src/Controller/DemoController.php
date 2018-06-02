<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:33
 */

namespace App\Controller;


use App\Entity\Authorization;
use App\Entity\Client;
use App\Services\OAuth;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

class DemoController extends AbstractController
{
    /**
     * @var OAuth
     */
    private $oauth;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(OAuth $oauth, EntityManagerInterface $em)
    {
        $this->oauth = $oauth;
        $this->em = $em;
    }

    /**
     * @Route("/")
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $clientsRepository = $this->em->getRepository(Client::class);

        $client = $clientsRepository->findOneBy(['clientName' => 'demo']);
        if (!$client) {
            throw $this->createNotFoundException('Missing demo client');
        }
        $state = bin2hex(random_bytes(5));
        $request->getSession()->set('state', $state);

        $authorizationResponse = $request->getSession()->get('authorization_response');
        $accessTokenResponse = $request->getSession()->get('access_token_response');
        $resourceOwnerResponse = $request->getSession()->get('resource_owner_response');
        $request->getSession()->remove('authorization_response');
        $request->getSession()->remove('access_token_response');
        $request->getSession()->remove('resource_owner_response');

        return $this->render('index.html.twig', [
            'client' => $client,
            'state' => $state,
            'authorization_response' => $authorizationResponse,
            'access_token_response' => $accessTokenResponse,
            'resource_owner_response' => $resourceOwnerResponse
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \OAuth2\Exceptions\OAuthException
     * @Route("/demo/resourceowner")
     */
    public function resourceOwner(Request $request) {
        $bearerToken = $request->request->get('bearer_token');
        $requiredScopes = explode(' ', $request->request->get('required_scope'));
        $requestPsr = ServerRequestFactory::fromGlobals();

        switch ($request->request->get('bearer_authentication_method')) {
            case 'authorization_request_header_field':
                $requestPsr = $requestPsr->withHeader('Authorization', 'Bearer '.$bearerToken);
        }

        if($errorResponse = $this->oauth->getResourceServer()->verifyRequest($requestPsr, $requiredScopes, 'demo')) {
            $response = [
                'WWW-Authenticate' => $errorResponse->getHeader('WWW-Authenticate')[0],
                'status' => $errorResponse->getStatusCode()
            ];
        }
        else {
            $authenticatedRequest = $this->oauth->getResourceServer()->getAuthenticatedRequest();
            $response = [
                'client' => $authenticatedRequest->getClient()->getIdentifier(),
                'scopes' => implode(' ', $authenticatedRequest->getScopes()),
            ];
            if($authenticatedRequest->getResourceOwner()) {
                $response['resource_owner'] = $authenticatedRequest->getResourceOwner()->getIdentifier();
            }
        }
//        VarDumper::dump($response);
//        VarDumper::dump($this->oauth->getResourceServer()->getAuthenticatedRequest());
//        die;
        $request->getSession()->set('resource_owner_response', $response);

        return $this->redirectToRoute('app_demo_index');
    }

    /**
     * Middleware permettant de récupérer le retour de l'authorization endpoint mais lorsque celui ci est mis
     * dans le fragment de l'url de retour. Sert uniquement pour les besoins de la demonstration.
     * En condition reelles, à aucun moment le fragment de l'url n'est récupéré et utilisé par le serveur.
     *
     * @param Request $request
     * @param OAuthController $authController
     * @return \Psr\Http\Message\ResponseInterface
     * @Route("/demo/oauth/authorize")
     */
    public function oauthAuthorize(Request $request, OAuthController $authController)
    {
        $response = $authController->authorize($request);
        if ($response->getStatusCode() == 302) {
            if ($location = $response->getHeader('Location')[0] ?? null) {
                $data = parse_url($location);
                $data = empty($data['query']) ? $data['fragment'] : $data['query'];
                parse_str($data, $data);
                $keys = ['access_token', 'token_type', 'expires_in', 'scope', 'id_token'];

                foreach ($keys as $key) {
                    if (isset($data[$key])) {
                        $request->getSession()->set($key, $data[$key]);
                    }
                }
            }
        } else {
            $data = json_decode($response->getBody()->__toString(), true);
        }

        $request->getSession()->set('authorization_response', $data);

        return $response;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/demo/oauth/token")
     * @throws \Exception
     */
    public function oauthToken(Request $request, OAuthController $authController)
    {
        $response = json_decode($authController->token($request)->getBody()->__toString(), true);

//        $response = $request->request->all();
        $keys = ['access_token', 'token_type', 'expires_in', 'scope', 'refresh_token', 'id_token'];

        foreach ($keys as $key) {
            if (isset($response[$key])) {
                $request->getSession()->set($key, $response[$key]);
            }
        }

        $request->getSession()->set('access_token_response', $response);

        return $this->redirectToRoute('app_demo_index');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/demo/oauth/callback")
     * @throws \Exception
     */
    public function oauthCallback(Request $request)
    {
        //todo CSRF
        $response = $request->query->all();
        $keys = ['state', 'code', 'access_token', 'expires_in', 'scope', 'token_type', 'id_token'];
        foreach ($keys as $key) {
            if (isset($response[$key])) {
                $request->getSession()->set($key, $response[$key]);
            }
        }

        return $this->redirectToRoute('app_demo_index');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/authorization/{id}/remove")
     */
    public function removeAuthorization($id)
    {
        $authorization = $this->em->getRepository(Authorization::class)->find($id);
        if (!$authorization) {
            throw $this->createNotFoundException();
        }
        if ($authorization->getResourceOwner() != $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $this->em->remove($authorization);
        $this->em->flush();

        return $this->redirectToRoute('app_demo_index');
    }
}