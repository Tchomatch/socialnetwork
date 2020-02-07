<?php

namespace App\DataFixtures;

use App\Entity\User;
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

        $manager->flush();

    }
}
