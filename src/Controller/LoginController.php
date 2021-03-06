<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est connecté il est renvoyé vers la home
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // pour obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // pour récupérer le dernier identifiant entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // On retourne nos variables à notre vue
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        //la fonction logout peut rester vide pour fonctionner et elle nous redirige vers la page renseigné dans le security.yaml avec le target
    }
}
