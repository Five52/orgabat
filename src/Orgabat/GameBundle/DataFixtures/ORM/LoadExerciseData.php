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
            [ 'Jeu 1', 'Approvisionnement', 3, 4, 2 ],
            [ 'Jeu 2', 'Approvisionnement', 5, 4, 1 ],
            [ 'Jeu 3', 'Approvisionnement', 1, 1, 7 ],
            [ 'Jeu 4', 'Déplacements', 3, 4, 7 ],
            [ 'Jeu 5', 'Déplacements', 4, 6, 4 ],
            [ 'Jeu 6', 'Déplacements', 2, 9, 5 ],
            [ 'Jeu 7', 'Circulation', 3, 3, 3 ],
            [ 'Jeu 8', 'Circulation', 4, 4, 4 ],
            [ 'Jeu 9', 'Circulation', 7, 1, 9 ],
            [ 'Jeu 10', 'Manutention', 2, 4, 8 ],
            [ 'Jeu 11', 'Manutention', 3, 8, 9 ],
            [ 'Jeu 12', 'Manutention', 9, 9, 5 ],
            [ 'Jeu 13', 'Protection', 8, 8, 1 ],
            [ 'Jeu 14', 'Protection', 5, 1, 6 ],
            [ 'Jeu 15', 'Protection', 3, 9, 8 ],
            [ 'Jeu 16', 'Organisation', 2, 7, 6 ],
            [ 'Jeu 17', 'Organisation', 2, 3, 1 ],
            [ 'Jeu 18', 'Organisation', 6, 5, 3 ],
            [ 'Jeu 19', 'Gestion de l\'espace de travail', 3, 9, 1 ],
            [ 'Jeu 20', 'Gestion de l\'espace de travail', 4, 8, 6 ],
            [ 'Jeu 21', 'Gestion de l\'espace de travail', 9, 6, 3 ],
            [ 'Jeu 22', 'Communication', 4, 6, 3 ],
            [ 'Jeu 23', 'Communication', 7, 9, 5 ],
            [ 'Jeu 24', 'Communication', 8, 4, 8 ]
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
