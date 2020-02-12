<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $postRepository, UserRepository $userRepository)
    {
        
        $allPost = $postRepository->findBy([], ['datepost' => 'DESC'], 10);


        return $this->render('home/index.html.twig', [
            'allPost' => $allPost,
        ]);

    }

    /**
     * @Route("/search", name="search")
     */
    public function search(UserRepository $userRepo, Request $request)
    {
        
        $contentSearch = $request->get('search');
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
        
        $contentSearch = $request->get('search');
        $searchUsers= $userRepo->findUser($contentSearch);
        
        return $this->render('home/searchDyn.html.twig', [
            'usersRechercher' => $searchUsers,
        ]);

    }

   
}

