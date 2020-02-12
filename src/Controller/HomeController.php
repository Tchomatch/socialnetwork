<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $postRepository)
    {
        // envoie une requête des 6 premiers post sur la page d'accueil
        $allPost = $postRepository->findBy([], ['datepost' => 'DESC'], 6);

        return $this->render('home/index.html.twig', [
            'allPost' => $allPost,
        ]);

    }

    /**
     * @Route("/load/{id}", name="load")
     */
    public function loadMore(PostRepository $postRepository, $id)
    {
        // la route load/{id} sera chargée par une requête ajax au scrolling de la page
        //  envoi une requête des 6 premier post à compter de l'id renvoyé en get
        $allPost = $postRepository->findBy([], ['datepost' => 'DESC'], 6, $id);


        return $this->render('home/load.html.twig', [
            'allPost' => $allPost,
        ]);

    }


}