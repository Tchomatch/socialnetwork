<?php

namespace App\Controller;

use App\Entity\Information;
use App\Repository\InformationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile_show")
     */
    public function profile_show($id, UserRepository $userRepository, InformationRepository $informationRepository)
    {

        $user = $userRepository->find($id);
        $userInformation = $user->getInformation();
        dump($user);
        dump($userInformation);
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'information' => $userInformation
        ]);
    }
}
