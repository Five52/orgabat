<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('Orgabat\GameBundle\Entity\Apprentice');

        $userManager = $this->container->get('pugx_user_manager');

        $dataApp = [
            ['Jacques', 'Dupont', 'jacques.dupont@mail.com', '12345678', 'CAP 1'],
            ['Jean', 'Duval', 'jean.duval@mail.fr', '23456789', 'CAP 1'],
            ['Yves', 'Boulanger', 'yves.boulanger@autremail.net', 'yvesboulanger', 'BEP 2']
        ];

        $dataTeach = [
            ['Prof', 'Test', 'prof.test@mail.com', '12345678', 'CAP 1'],
            ['Autre', 'Prof', 'autre.prof@mail.fr', '12345678', 'BEP 2'],
        ];


        foreach ($dataApp as $line) {

            $apprentice = $userManager->createUser();
            $apprentice->setFirstName($line[0]);
            $apprentice->setLastName($line[1]);
            $apprentice->setUsername($line[0].' '.$line[1]);
            $apprentice->setEmail($line[2]);
            $apprentice->setPlainPassword($line[3]);

            $section = $manager
                ->getRepository('OrgabatGameBundle:Section')
                ->findOneByName($line[4])
            ;

            $apprentice->setSection($section);
            $apprentice->setEnabled(true);
            $apprentice->addRole('ROLE_APPRENTICE');
            $userManager->updateUser($apprentice, true);
        }

        $discriminator->setClass('Orgabat\GameBundle\Entity\Trainer');

        foreach ($dataTeach as $line) {

            $trainer = $userManager->createUser();
            $trainer->setFirstName($line[0]);
            $trainer->setLastName($line[1]);
            $trainer->setUsername($line[0].' '.$line[1]);
            $trainer->setEmail($line[2]);
            $trainer->setPlainPassword($line[3]);

            $section = $manager
                ->getRepository('OrgabatGameBundle:Section')
                ->findOneByName($line[4])
            ;

            $trainer->setSection($section);
            $trainer->setEnabled(true);
            $trainer->addRole('ROLE_TRAINER');
            $userManager->updateUser($trainer, true);
        }

        $discriminator->setClass('Orgabat\GameBundle\Entity\Admin');
        $userManager = $this->container->get('pugx_user_manager');
        $admin = $userManager->createUser();
        $admin->setFirstName('Admin');
        $admin->setLastName('Admin');
        $admin->setUsername('Admin');
        $admin->setEmail('admin@admin.com');
        $admin->setPlainPassword('12345678');
        $admin->setEnabled(true);
        $admin->addRole('ROLE_TRAINER');
        $userManager->updateUser($admin,true);

        // $user1 = new User();
        // $user1->setUsername('Jacques Dupont');
        // $user1->setEmail('jacques.dupont@mail.com');
        // # Mot de passe : jacques
        // $user1->setPassword('$2a$06$3KbpQXiOY.FBN3RebaXxx.B1pZfvmj.SpEDVu.ww7JuawckMMEIai');

        // $user2 = new User();
        // $user2->setUsername('Marcel Arthur');
        // $user2->setEmail('marcel.arthur@mail.com');
        // # Mot de passe : marcel
        // $user2->setPassword('$2a$06$fbUoY7JSvqq4B2Zq8s3V9eqc7HLY5gfI0Q5tU0CPB/pqrWtRhAVWe');

        // $user3 = new User();
        // $user3->setUsername('Thérèse Martin');
        // $user3->setEmail('therese.martin@mail.com');
        // # Mot de passe : therese
        // $user3->setPassword('$2a$06$20YD0Xw31Wc8GuiTTf95h.FM7aifrAWMCNSwT.5NWIeSHs6nseL1K');

        // $manager->persist($user1);
        // $manager->persist($user2);
        // $manager->persist($user3);

        // // On déclenche l'enregistrement de tous les tags
        // $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}