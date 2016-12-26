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
        $data = [
            [100, 1, 1, 2, 'Jeu 1', 'Jacques Dupont'],
            // [112, 4, 0, 1, 'Jeu 2', 'Jacques Dupont'],
            [95, 1, 1, 6, 'Jeu 3', 'Jacques Dupont']
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
        return 5;
    }
}