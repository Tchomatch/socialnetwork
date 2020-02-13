<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\ImagePost;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $postRepository, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
      
        // Instanciation d'un nouveau post
        $post = new Post();
        $form = $this->createForm(PostType::class, $post); // Création du formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // récupération des données date et user_id 
            $date = new \DateTime();
            $post->setDatepost($date);
            $post->setUser($user);

            $files = $form->get('image')->getData();

            // Recuperation de l'image et traitement
            if ($files) {
                foreach ($files as $file) {
                    $imagePost = new ImagePost();

                    // Déplace le fichier dans le dossier où les images sont stockées
                    $newFilename ='img_' . uniqid().'.'.$file->guessExtension();

                    try {

                        $file->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );

                    } catch (FileException $e) {

                        // ... Si quelque chose se passe mal pendant l'upload

                    }   

                    // J'enregistrer dans le post et dans la base image
                    $imagePost->setImage($newFilename);
                    $imagePost->setPost($post);
                    $entityManager->persist($imagePost);
                    $post->addImagePost($imagePost);
                }

            }

            $entityManager->persist($post);

            // Empecher une post null (de part la photo ou le contenu)
            if(null != $post->getContenu() || !empty($file)){
               $entityManager->flush(); 
            }

            // renvoie vers la page profile pour raffraichir la page
            return $this->redirectToRoute('home');
        }

        // envoie une requête des 6 premiers post sur la page d'accueil
        $allPost = $postRepository->findBy([], ['datepost' => 'DESC'], 6);

        // Affichage du formulaire et des variables
        return $this->render('home/index.html.twig', [
            'allPost' => $allPost,
            'user' => $user,
            'addPost' => $form->createView()
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
        $searchUsers= $userRepo->findUser($contentSearch, 3);
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

