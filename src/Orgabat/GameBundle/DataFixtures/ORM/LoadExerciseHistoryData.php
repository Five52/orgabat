<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\ExerciseHistory;

class LoadExerciseHistoryData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $data = [
            [150, 0, 0, 1, 'Jeu 1', 'Jacques Dupont'],
            [100, 1, 1, 2, 'Jeu 1', 'Jacques Dupont'],
            // [112, 4, 0, 1, 'Jeu 2', 'Jacques Dupont'],
            [95, 1, 1, 6, 'Jeu 3', 'Jacques Dupont'],
            [30, 1, 1, 7, 'Jeu 3', 'Jean Duval']
        ];

        foreach ($data as $line) {
            $eh = new ExerciseHistory();
            $eh->setTimer($line[0]);
            $eh->setHealthNote($line[1]);
            $eh->setOrganizationNote($line[2]);
            $eh->setBusinessNotorietyNote($line[3]);

            $exercise = $manager
                ->getRepository('OrgabatGameBundle:Exercise')
                ->findOneByName($line[4])
            ;
            $eh->setExercise($exercise);
            $exercise->setBestExerciseHistory($eh);

            $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
            $discriminator->setClass('Orgabat\GameBundle\Entity\Apprentice');
            $userManager = $this->container->get('pugx_user_manager');
            $user = $userManager->findUserBy(['username' => $line[5]]);
            $eh->setUser($user);

            $manager->persist($eh);
        }

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}
