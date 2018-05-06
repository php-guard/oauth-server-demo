<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 28/04/2018
 * Time: 16:14
 */

namespace App\Controller;


use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class ClientsController extends AbstractController
{

    /**
     *
     * @Route("/clients")
     */
    public function index() {
        $clients = $this->getDoctrine()->getRepository(Client::class)->findAll();
        return $this->render('clients/index.html.twig', ['clients' => $clients]);
    }

    /**
     *
     * @Route("/clients/add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request) {

        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_index');
        }

        return $this->render('clients/form.html.twig', ['form' => $form->createView()]);
    } /**
     *
     * @Route("/clients/edit/{identifier}")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, $identifier) {

        $client = $this->getDoctrine()->getRepository(Client::class)->findOneBy([
            'identifier' =>$identifier
        ]);
        if(!$client) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_index');
        }

        return $this->render('clients/form.html.twig', ['form' => $form->createView()]);
    }
}