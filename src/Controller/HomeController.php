<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/search", name="search")
     */
    public function search(UserRepository $userRepo, Request $request)
    {
        // je récupère le contenu de ma barre de recherche avec le name de mon input
        $contentSearch = $request->get('search');
        // j'éxécute me requete qui me récupère tout les user lié a la recherche par leur pseudo
        $searchUsers= $userRepo->findUser($contentSearch);
        // condition pour afficher aucun utilisateur lorsque mon input est vide
        if (empty($contentSearch)){
            $searchUsers = [];
        }
        return $this->render('home/search.html.twig', [
            'usersRechercher' => $searchUsers,
        ]);

    }

   
    /**
     * @Route("/search2", name="search2")
     */
    public function search2(UserRepository $userRepo, Request $request)
    {
        // nouvelle route pour nouvelle page pour ma barre de recherche dynamique 
        
        $contentSearch = $request->get('search');
        // sur mon affichage dynamique je lui renseigne une limite pour ne pas m'afficher tous les user lié a la recherche
        $searchUsers= $userRepo->findUser($contentSearch, 5);
        if (empty($contentSearch)){
            $searchUsers = [];
        }
        return $this->render('home/searchDyn.html.twig', [
            'usersRechercher' => $searchUsers,
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

