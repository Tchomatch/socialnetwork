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
    # A prevoir une autre route "/conversation/{<id de la conversation recherchée>}"
    # A prevoir un écran conversations

    /**
     * @Route("/profile/{id}/chat", name="chat")
     */
    public function chat($id, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository)
    {
        $userReceiver = $userRepository->find($id);
        $userSender =  $userRepository->find(4); // $this->getUser()
        $userSenderId = $userSender->getId();
        $conversation = $conversationRepository->findConv($id, $userSenderId);

        $message = new Message();

        // je génère un formulaire pour pouvoir envoyer un message
        $form = $this->createForm(MessageType::class, $message);

        // je recupere les données du form
        $form->handleRequest($request);

        // si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // je vérifie si une conversation entre les deux utilisateur existe 
            if  (!empty($conversation)) {
                $message->setConversation($conversation);
            } else { // si non je créé une nouvelle conversation
                $conversation = new Conversation();

                $conversation->setUserReceiver($userReceiver);
                $conversation->setUserSender($userSender);

                // j'enregistre ma conversation dans ma BDD
                $entityManager->persist($conversation);
                $message->setConversation($conversation);
            }

            $message->setDateEnvoi(new \DateTime());
            $message->setUserSender($userSender);
            $message->setUserReceiver($userReceiver);

            // je procede a l'enregistrement de mes données messages
            $entityManager->persist($message);
        
            $files = $form->get('image')->getData();
           
            if ($files) {
                
                // Move the file to the directory where brochures are stored
                foreach ($files as $file){
                    $newFilename ='img_' . uniqid().'.'.$file->guessExtension();
                   
                    try {
                        $file->move(
                            $this->getParameter('images_original'),
                            $newFilename
                        );
                        // $cheminImage = $this->getParameter('short_images_original'). $newFilename;
                        // switch($file->guessExtension())
                        // {
                        // case "png":
                        //     $sourceImage = imagecreatefrompng($cheminImage);
                        //     break;
                        // case "gif":
                        //     $sourceImage = imagecreatefromgif($cheminImage);
                        //     break;
                        // default:
                        //     $sourceImage = imagecreatefromjpeg($cheminImage);
                        //     break;
                        // }

                        // $destinationMin = imagecreatetruecolor(100, 100);    
                        // # Je souhaite connaître la largeur de mon image ciblée
                        // $widthImage = imagesx($sourceImage);
                        // # Je souhaite connaître la hauteur de mon image ciblée
                        // $heightImage = imagesy($sourceImage);
    
                        // imagecopyresampled($destinationMin, $sourceImage, 0, 0, 0, 0, 100, 100, $widthImage, $heightImage);

                        // $file->move(
                        //     $this->getParameter('images_miniature'),
                        //     $newFilename
                        // );
                        // $cheminMin =  $this->getParameter('short_images_miniature'). $newFilename;
                        // imagejpeg($destinationMin, $cheminMin, 100);
                    } catch (FileException $e) {
                       
                        // ... handle exception if something happens during file upload
                    }
                
                    // updates the 'brochureFilename' property to store the jpg file name
                    // instead of its contents
                    $imageChat = new ImageChat();
                    $imageChat->setMessage($message);
                    $imageChat->setImage($newFilename);
                    $entityManager->persist($imageChat);
                    $message->addImageChat($imageChat);
                } 
               
            }
            
            // j'enregistre les données en BDD
            if( $message->getContenu()!== null || !empty($file) ){
                
                $entityManager->flush();
            }
            
           

            //return $this->redirectToRoute('chat', ['id' => $userReceiver->getId()]);
        } 
        $msg = $conversation->getMessages();
        
        return $this->render('chat/index.html.twig', [
            'formMessage' => $form->createView(),
            'messages' => $msg,
            'userReceiver' => $userReceiver,
            
            
        ]);
    }
}
