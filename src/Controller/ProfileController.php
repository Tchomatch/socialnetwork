<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Information;
use App\Repository\UserRepository;
use App\Repository\InformationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile_show")
     */
    public function profile_show($id, UserRepository $userRepository, Request $request)
    {
        $user = $userRepository->find($id);
        $userInformation = $user->getInformation();

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $this->getUser();
            $date = new \DateTime();

            $post->setDatepost($date);
            $post->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'information' => $userInformation,
            'addPost' => $form->createView()
        ]);
    }
}
