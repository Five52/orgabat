<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setFirstName('Jacques');
        $user1->setLastName('Ouille');
        $user1->setPassword('jacques');

        $user2 = new User();
        $user2->setFirstName('Marcel');
        $user2->setLastName('Osef');
        $user2->setPassword('marcel');

        $user3 = new User();
        $user3->setFirstName('Thérèse');
        $user3->setLastName('Rèserèse');
        $user3->setPassword('therese');

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }
}