<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Information;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $user = new User();
       $user->setEmail('souhib@souhib.com')
            ->setPassword('souhib')
            ->setRoles(['ROLE_USER'])
            ->setPseudo('souhib')
            ->setImage('https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/Siberischer_tiger_de_edit02.jpg/290px-Siberischer_tiger_de_edit02.jpg');

        $manager->persist($user);

        $user = new User();
        $user->setEmail('souhib@hadjila.com')
            ->setPassword('souhib')
            ->setRoles(['ROLE_USER'])
            ->setPseudo('so')
            ->setImage('https://download.vikidia.org/vikidia/fr/images/thumb/d/d2/Lion.jpg/250px-Lion.jpg');
        
        $manager->persist($user);

        $user = new User();
        $user->setEmail('hadjila@hadjila.com')
            ->setPassword('souhib')
            ->setRoles(['ROLE_USER'])
            ->setPseudo('souh')
            ->setImage('https://download.vikidia.org/vikidia/fr/images/thumb/d/d2/Lion.jpg/250px-Lion.jpg');
        
        $manager->persist($user);

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
