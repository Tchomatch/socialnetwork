<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(PostRepository $postRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // mettre un utilisateur admin

        $affichagePost = $postRepository->findAll();
        // J'affiche tout les posts 

        return $this->render('admin/index.html.twig', [
            'affichagePosts' => $affichagePost,
        ]);
    }
}
