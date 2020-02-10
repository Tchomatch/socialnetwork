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

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile_show")
     */
    public function profile_show($id, UserRepository $userRepository, Request $request, PostRepository $postRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $userRepository->find($id);
        $userInformation = $user->getInformation();

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $date = new \DateTime();

            $post->setDatepost($date);
            $post->setUser($user);

            $files = $form->get('image')->getData();

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

                    // instead of its contents
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

        $affichagePost = $postRepository->findBy([], ['id' => 'DESC'], 1);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'information' => $userInformation,
            'addPost' => $form->createView(),
            'affichagePosts' => $affichagePost
        ]);
    }
}
