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
        // mettre un utilisateur admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // J'affiche tout les posts 
        $affichagePost = $postRepository->findAll();
        
        // affichage des variables de la vue
        return $this->render('admin/index.html.twig', [
            'affichagePosts' => $affichagePost,
        ]);
    }
}
