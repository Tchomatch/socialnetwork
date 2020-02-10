<?php

namespace App\Controller;

use App\Form\SettingsType;
use App\Entity\Information;
use App\Form\InformationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     */
    public function registrationForm(Request $request, SettingsType $settingsType, EntityManagerInterface $entityManager, InformationRepository $informationRepository)
    {
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
                'modif', 'Votre Profil a bien été modifié'
            );
        }

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
        }

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView()
        ]);
    }
}