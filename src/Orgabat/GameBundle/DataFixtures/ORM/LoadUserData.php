<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
<<<<<<< HEAD
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

        $user4 = new User();
        $user4->setFirstName('User');
        $user4->setLastName('Test');
        $user4->setPassword('user');

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
=======
        // Liste des noms de tags à ajouter
        $firstNames = array(
            'Jacques',
            'Marcel',
            'Thérèse'
        );

        $lastNames = array(
            'Ouille',
            'Osef',
            'Rèserèse'
        );

        

        foreach ($names as $name) {
            // On crée le tag
            $category = new Tag();
            $category->setName($name);

            // On la persiste
            $manager->persist($category);
        }
>>>>>>> 9796dcb91365a10fb4dd0201728e39332560a59c

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }
}