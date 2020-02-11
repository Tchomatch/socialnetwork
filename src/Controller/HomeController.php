<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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


}