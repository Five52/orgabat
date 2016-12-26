<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\Exercise;

class LoadExerciseData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [
            ['Jeu 1', 'Approvisionnement', 3, 4, 2],
            ['Jeu 2', 'Approvisionnement', 5, 4, 0],
            ['Jeu 3', 'Approvisionnement', 1, 1, 7]
        ];

        foreach ($data as $line) {
            $exercise = new Exercise;
            $exercise->setName($line[0]);

            $category = $manager
                ->getRepository('OrgabatGameBundle:Category')
                ->findOneByName($line[1])  
            ;

            $exercise->setCategory($category);
            $exercise->setHealthMaxNote($line[2]);
            $exercise->setOrganizationMaxNote($line[3]);
            $exercise->setBusinessNotorietyMaxNote($line[4]);

            $manager->persist($exercise);
        }

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}