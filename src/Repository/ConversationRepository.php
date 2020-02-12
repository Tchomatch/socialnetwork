<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    
    public function findConv($id, $userSenderId)
    {
        return $this->createQueryBuilder('conversation')
            ->andWhere('conversation.userReceiver = :user_receiver_id and conversation.userSender = :user_sender_id or conversation.userReceiver = :user_sender_id and conversation.userSender = :user_receiver_id')
            ->setParameters([
                'user_receiver_id'=> $id,
                'user_sender_id' => $userSenderId])
            ->getQuery()
            ->getOneOrNullResult() // pour récupérer une seul conversation
        ;
    }

    // /**
    //  * @return ImagePost[] Returns an array of ImagePost objects
    //  */
    public function findConversationsById($userSenderId)
    {
        //13
        return $this->createQueryBuilder('conversation')
            ->andWhere('conversation.userReceiver = :user_receiver_id 
            or conversation.userSender = :user_sender_id')
            ->setParameters([
                'user_receiver_id'=> $userSenderId,
                'user_sender_id' => $userSenderId])
            // ->leftJoin('Message', 'm', 'WITH', 'm.conversation.id = conversation.id')
            // ->innerJoin('Namespace\YourBundle\Entity\Aq_skill', 'ac', 'WITH', 'ac.user_id = u.user_id'
            //->orderBy('message.date_envoi', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
