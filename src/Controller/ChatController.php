<?php

namespace App\Controller;

use DateTime;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Entity\ImageChat;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ChatController extends AbstractController
{
    /**
     * @Route("/profile/{id}/chat", name="chat")
     */
    public function chat($id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        // $userReceiver est l'utilisateur avec qui je chat, je récupère son id dans l'url 
        $userReceiver = $userRepository->find($id);
        // $userSender représente l'utilisateur connecté sur son appareil (moi)
        $userSender = $this->getUser();
        // je récupère l'id du sender 
        $userSenderId = $userSender->getId();
        // je récupère la conversation entre 2 utilisateurs   (moi et un autre)
        $conversation = $conversationRepository->findConv($id, $userSenderId);

        $message = new Message();

        // je génère un formulaire pour pouvoir envoyer un message
        $form = $this->createForm(MessageType::class, $message);

        // je recupere les données du formulaire message
        $form->handleRequest($request);
        // je vérifie si une conversation entre les deux utilisateur existe sinon  j'en crée une nouvelle
        if  ($conversation === null) {
        
            $conversation = new Conversation();

            // je set l'utilisateur qui recois le premier message
            $conversation->setUserReceiver($userReceiver);
            // je set l'utilisateur envoie le premier message
            $conversation->setUserSender($userSender);

            // j'enregistre ma conversation 
            $entityManager->persist($conversation);
            
        }
        // Je suis dans le cas où j'envoie les message donc je suis tjrs le sender et lui le receiver 
        // si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            
            // je récupère la conversation
            $message->setConversation($conversation);
            // je set la date et l'heure quand le message est envoyé
            $message->setDateEnvoi(new \DateTime());
            // je set le userSender (moi)
            $message->setUserSender($userSender);
            // je set le userReceiver (l'autre user)
            $message->setUserReceiver($userReceiver);

            // je procede a l'enregistrement de mes données messages
            $entityManager->persist($message);
        
            // je crée la variable $files qui est mes données entrer dans l'upload de fichier
            $files = $form->get('image')->getData();
           
            // si un ou des fichiers sont uploads alors je rentre dans la condition
            if ($files) {
                
                // je crée une boucle car mon ou mes images sont une collection
                foreach ($files as $file){
                    // je donne un nvx nom a mon fichier
                    $newFilename ='img_' . uniqid().'.'.$file->guessExtension();
                   
                    try {
                        // je déplace mon fichier ou je le souhaite
                        $file->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                        
                    } catch (FileException $e) {
                       
                        // si erreur lors de l'envoie d'un fichier
                    }
                
                    // j'instancie une nouvelle image
                    $imageChat = new ImageChat();
                    // je set mon message lié a mon image
                    $imageChat->setMessage($message);
                    // je set le nouveau nom de mon fichier 
                    $imageChat->setImage($newFilename);
                    // jenregistre mon image 
                    $entityManager->persist($imageChat);
                    // je lie mon image a mon message
                    $message->addImageChat($imageChat);
                } 
               
            }
            
            // j'enregistre les données en BDD que si un fichier est upload ou le contenu est rempli  ou les deux
            if( $message->getContenu()!== null || !empty($file) ){
                
                $entityManager->flush();
            }
            
            return $this->redirectToRoute('chat', ['id' => $userReceiver->getId()]);
        } 
        
        // je récupère tous les messages lié a ma conversation récupérée au dessus grace au findConv() 
        $msg = $conversation->getMessages();
       
        
        return $this->render('chat/index.html.twig', [
            'formMessage' => $form->createView(),
            'messages' => $msg,
            'userReceiver' => $userReceiver,
            
        ]);
    }

     /**
     * @Route("/chat/{id}", name="messages")
     */
    public function messages($id, UserRepository $userRepository, ConversationRepository $conversationRepository)
    {
        // nouvelle fonction qui renvoie à une autre vue pour pouvoir rendre mon chat dynamique avec du ajax
        $userReceiver = $userRepository->find($id);
        $userSender =   $this->getUser();
        $userSenderId = $userSender->getId();
        $conversation = $conversationRepository->findConv($id, $userSenderId);

        // je récupère tous les messages lié a ma conversation 
        $msg = $conversation->getMessages();
        

        return $this->render('chat/messages.html.twig', [
            'messages' => $msg,
            'userReceiver' => $userReceiver,
            
            
        ]);
    }   
}
