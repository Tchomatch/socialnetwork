<?php

namespace App\Controller;

use App\Entity\ImagePost;
use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Information;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile_show")
     */
    public function profile_show($id, UserRepository $userRepository, Request $request, PostRepository $postRepository, UserInterface $userInterface)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // recuperation de l'id dans l'url
        $user = $userRepository->find($id);
        // recuperation des informations du formulaire information
        $userInformation = $user->getInformation();

        // création d'un utilisateur connecté pour différencier chaque post de chaque user
        $userConnect = $userInterface;

        // Instanciation d'un nouveau post
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
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

                        // ... handle exception if something happens during file upload

                    }   // updates the 'brochureFilename' property to store the jpg file name

                    // J'enregistrer dans le post et dans la base image
                    $imagePost->setImage($newFilename);
                    $imagePost->setPost($post);
                    $entityManager->persist($imagePost);
                    $post->addImagePost($imagePost);
                }
                
            }
            
            $entityManager->persist($post);

            if(null != $post->getContenu() || !empty($file)){
               $entityManager->flush(); 
            }
            
        }

        // Requete SQL
        $affichagePost = $postRepository->findBy(['user' => $id], ['datepost' => 'DESC'], 1);

        // Affichage des formulaire et des informations
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'information' => $userInformation,
            'addPost' => $form->createView(),
            'affichagePosts' => $affichagePost,
            'userConnect' => $userConnect
        ]);
    }
}
