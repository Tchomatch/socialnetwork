<?php

namespace App\Controller;


use App\Entity\Conversation;
use App\Repository\ConversationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConversationController extends AbstractController
{
    /**
     * @Route("/conversation", name="conversation")
     */
    public function index(ConversationRepository $conversationRepository)
    {

        // Je récupère l'utilisateur connecté
        $user = $this->getUser();

        // Je récupère les conversations de cet utilisateur 
        $conversations = $conversationRepository->findConversationsById($user->getId());

        // dump($conversations);

        // Je retourne ses conversations vers ma vue
        return $this->render('conversation/index.html.twig', [
            'conversations' => $conversations,

        ]);
    }
}