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
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SettingsController extends AbstractController
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    
    
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
            
            // j'ajoute un message pour alerter le user
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
        public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
        {
            // récupération de l'EM, création du formulaire, récupération de l'utilisateur, association du formulaire à une requête en bdd
            
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $form = $this->createForm(ChangePasswordType::class, $user);
            $form->handleRequest($request); 
            // $plainPassword = $user->getPassword();
            
            // dump($user->getPassword());
            // si les données transmise dans le formulaire sont valides
            if ($form->isSubmitted() && $form->isValid()) {
                // on définit une variable $oldPassword qui récupère dans les données transmise par l'utilisateur au champ oldPassword formulaire le password 
                $oldPassword = $request->request->get('change_password')['oldPassword'];
                // dd($request->request);
                // Si l'ancien mot de passe est bon
                if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                    
                    $newPassword = $form->get('newPassword')->getData();
                    $newEncodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
                    $user->setPassword($newEncodedPassword);
                    
                    $entityManager->persist($user);
                    $entityManager->flush();
                    // j'ajoute un message flash pour alerter le user
                    $this->addFlash(
                        'modif', 'Votre Mot de passe a bien été modifié !'
                    );
                } else {
                    $form->addError(new FormError('Votre ancient mot de passe est incorrect veuillez réessayer'));
                }
            }
            
            return $this->render('settings/password_edit.html.twig', [
                'form' => $form->createView(),
                ]);
            }
        }
        
        
        
        