<?php

namespace App\Controller;

use App\Form\SettingsType;
use App\Entity\Information;
use App\Form\InformationType;
use App\Form\ChangePasswordType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
USE Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     */
    public function registrationForm(Request $request, SettingsType $settingsType, EntityManagerInterface $entityManager, InformationRepository $informationRepository)
    {
        // Je récupère l'utilisateur connecté
        $user = $this->getUser();
        $form = $this->createForm(SettingsType::class, $user);

        // je recupere les données du form
        $form->handleRequest($request);

        // si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            // je procede a l'enregistrement de mes données
            $entityManager->persist($user);

            // j'enregistre les données en BDD
            $entityManager->flush();

            // j'ajoute un message flash pour alerter le user
            $this->addFlash(
                'modif',
                'Votre Profil a bien été modifié'
            );
        }

        // Je récupère les informations de l'utilisateurs connecté
        $information = $user->getInformation();

        if ($information === null ) {
            $information = new Information();
        }

        // Je genere le formulaire information
        $form2 = $this->createForm(InformationType::class, $information);

        // je recupere les données du form
        $form2->handleRequest($request);

        // si les données sont valides
        if ($form2->isSubmitted() && $form2->isValid()) {

            $information->setUser($user);
            // je procede a l'enregistrement de mes données
            $entityManager->persist($information);

            // j'enregistre les données en BDD
            $entityManager->flush();

            // j'ajoute un message flash pour alerter le user
            $this->addFlash(
                'modif',
                'Votre Profil a bien été modifié'
            );
        }

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView()
        ]);
    }

    /**
     * @Route("/settings/editPassword", name="editPassword")
     */
    public function editPassword(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
    
        // si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
    
            $passwordEncoder = $this->get('security.password_encoder');
            $oldPassword = $request->request->get('password_edit')['oldPassword'];

            // Si l'ancien mot de passe est bon
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($newEncodedPassword);
            
            $entityManager->persist($user);
            $entityManager->flush();

            // j'ajoute un message flash pour alerter le user
            $this->addFlash(
                'modif', 'Votre Mot de passe a bien été modifié !'
            );
            } else {
                $form->addError(new FormError('Votre ancien mot de passe est incorrect veuillez réessayer'));
            }
        }

        return $this->render('settings/password_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}



