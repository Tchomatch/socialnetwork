<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Information;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('Tartempion@gmail.com')
        ->setRoles(['ROLE_USER'])
        ->setPassword('bzfjkabfj')
        ->setPseudo('Tartempion')
        ->setImage('http://img.over-blog-kiwi.com/0/93/22/56/20150118/ob_606c9f_pedagogie-par-l-image.jpg');
        $manager->persist($user);

        $information = new Information();
        $information->setNom('nom')
        ->setPrenom('prenom')
        ->setDateNaissance(new \DateTime())
        ->setAdresse('Rue des papillons')
        ->setVille('Lyon')
        ->setCp('69009')
        ->setDescription('Bonjour a tous !')
        ->setUser($user);
        $manager->persist($information);

        $manager->flush();
    }
}
