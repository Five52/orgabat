<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\HistoryRealisation;

class LoadHistoryRealisationData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $data = [                                      // user_id
            [100, 1, 1, 2, 'Jeu 1', 'Jacques Dupont'], // 1
            [50, 2, 2, 2, 'Jeu 1', 'Jacques Dupont'],  // 1
            [150, 3, 1, 2, 'Jeu 1', 'Jacques Dupont'], // 1
            [50, 3, 4, 2, 'Jeu 1', 'Pierre Durant'],   // 2
            [95, 1, 1, 0, 'Jeu 3', 'Jacques Dupont']   // 1
        ];

        foreach ($data as $line) {
            $hr = new HistoryRealisation;
            $hr->setTimer($line[0]);
            $hr->setHealthNote($line[1]);
            $hr->setOrganizationNote($line[2]);
            $hr->setBusinessNotorietyNote($line[3]);

            $exercise = $manager
                ->getRepository('OrgabatGameBundle:Exercise')
                ->findOneByName($line[4])
            ;
            $hr->setExercise($exercise);

            $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
            $discriminator->setClass('Orgabat\GameBundle\Entity\Apprentice');
            $userManager = $this->container->get('pugx_user_manager');
            $user = $userManager->findUserBy(['username' => $line[5]]);
            $hr->setUser($user);

            $manager->persist($hr);
        }

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}