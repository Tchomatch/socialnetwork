<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\ImagePost;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile_show")
     * @Route("/profile/{id}", name="profile_show")
     */
    public function profile_show(UserRepository $userRepository, Request $request, PostRepository $postRepository, UserInterface $userInterface, $id = false)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        // création d'un utilisateur connecté pour différencier chaque post de chaque user
        $userConnect = $userInterface;

        if($id === false){
            $user = $this->getUser();
            $id = $user->getId();
        } else{
            $user = $userRepository->find($id);
        }

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

            // Ne peut pas rien envoyer soit une photo soit un contenu
            if(null != $post->getContenu() || !empty($file)){
               $entityManager->flush(); 
            }

            // renvoie vers la page profile pour raffraichir la page
            return $this->redirectToRoute('profile_show');
        }

        // Requete SQL
        $affichagePost = $postRepository->findAll();

        // recuperation des informations du formulaire information
        $userInformation = $user->getInformation();

        // Affichage des formulaire et des informations
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'information' => $userInformation,
            'addPost' => $form->createView(),
            'affichagePosts' => $affichagePost,
            'userConnect' => $userConnect
        ]);
    }

    /**
     * @Route("/profile/post-edit/{id}", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        // si on renvoie le formulaire de changement de post 
        // alors on le traite et on l'enregistre
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/post-delete/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post, UserInterface $userInterface): Response
    {

        // création d'une variable admin
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        
        $user = $this->getUser();
        $userConnect = $userInterface;

        if ($userConnect == $user || $userConnect == $user["ROLE_ADMIN"]){

            // Si le jeton (token) ets valide, alors on demande la suppréssion du post correspondant
            if($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();

                // on enregistre une liste d'image
                $listImage = $post->getImagePost();

                // on boucle la suppression d'une collection d'image
                // Car les clés étrangères doivent être supprimer avant
                foreach($listImage as $image){
                    $post->removeImagePost($image);
                }

                // puis on supprime le post
                $entityManager->remove($post);
                $entityManager->flush();

                // message flash de suppression
                $message = $this->addFlash(
                    'notice',
                    'Votre post a été supprimé'
                );

                if($userConnect == $hasAccess){ // admin renvoyer vers la page admin
                    return $this->redirectToRoute('admin');
                } else { // si on est user on renvoie vers profile
                    return $this->redirectToRoute('profile_show');
                }
                
            }
        }
        return $this->render('profile/index.html.twig', [
            'message' => $message
        ]);
    }

}
