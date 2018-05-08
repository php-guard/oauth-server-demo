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
use App\Entity\User;
use App\Services\OAuth;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

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
        $request->getSession()->remove('authorization_response');

        return $this->render('index.html.twig', [
            'client' => $client,
            'state' => $state,
            'authorization_response' => $authorizationResponse
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/oauth/callback")
     * @throws \Exception
     */
    public function oauthCallback(Request $request)
    {
        //todo CSRF
//VarDumper::dump($request);die;
        $response = $request->query->all();
        $keys = ['state', 'nonce', 'code', 'access_token', 'refresh_token', 'id_token'];
        foreach ($keys as $key) {
            if(isset($response[$key])) {
                $request->getSession()->set($key, $response[$key]);
            }
        }
        $request->getSession()->set('authorization_response', $response);

        return $this->redirectToRoute('app_demo_index');

        $client = $this->em->getRepository(Client::class)->findOneBy(['clientName' => 'demo']);
        if (!$client) {
            throw $this->createNotFoundException('Missing demo client');
        }

        $oldState = $request->getSession()->get('state');
        $state = bin2hex(random_bytes(5));
        $request->getSession()->set('state', $state);
        $response = $request->query->all();

        if ($request->getMethod() == 'POST') {
            $response = $this->forward(OAuthController::class . '::token', ['request' => $request]);
            $response = json_decode($response->getContent(), true);

        }

        return $this->render('callback.html.twig', [
            'response' => $response,
            'client' => $client,
            'state' => $state,
            'oldState' => $oldState
        ]);
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