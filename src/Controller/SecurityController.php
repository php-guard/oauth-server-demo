<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 01/05/2018
 * Time: 18:13
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\VarDumper\VarDumper;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            '_target_path' => $request->get('_target_path')
        ));
    }
}