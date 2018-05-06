<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:33
 */

namespace App\Controller;


use App\Entity\Client;
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
        $client = $this->em->getRepository(Client::class)->findOneBy(['clientName' => 'demo']);
        if (!$client) {
            throw $this->createNotFoundException('Missing demo client');
        }
        $state = bin2hex(random_bytes(5));
        $request->getSession()->set('state', $state);

        return $this->render('index.html.twig', [
            'client' => $client,
            'state' => $state
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
}