<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
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

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }
}