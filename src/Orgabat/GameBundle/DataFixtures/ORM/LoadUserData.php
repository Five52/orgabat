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

        // First Name, Last Name, Birth Date, Email, Section
        $userData = [
            ['Jacques', 'Dupont', '01012001', 'jacques.dupont@mail.com', 'CAP 1'],
            ['Jean', 'Duval', '01012001', 'jean.duval@mail.fr', 'CAP 1'],
            ['Yves', 'Boulanger', '01012001', 'yves.boulanger@autremail.net', 'BEP 2']
        ];

        // First Name, Last Name, Password, Email, Section(s)
        $trainerData = [
            ['Prof', 'Test', '12345678', 'prof.test@mail.com', 'CAP 1', 'BEP 2'],
            ['Autre', 'Prof', '12345678', 'autre.prof@mail.fr', 'BEP 2'],
        ];


        foreach ($userData as $line) {

            $apprentice = $userManager->createUser();
            $apprentice->setFirstName($line[0]);
            $apprentice->setLastName($line[1]);
            $apprentice->setUsername($line[0].' '.$line[1]);
            $apprentice->setBirthDate($line[2]);
            $apprentice->setPlainPassword($line[2]);
            $apprentice->setEmail($line[3]);

            $section = $manager
                ->getRepository('OrgabatGameBundle:Section')
                ->findOneByName($line[4])
            ;

            $apprentice->setSection($section);
            $apprentice->setEnabled(true);
            $apprentice->addRole('ROLE_APPRENTICE');
            $userManager->updateUser($apprentice);
        }

        $discriminator->setClass('Orgabat\GameBundle\Entity\Trainer');

        foreach ($trainerData as $line) {

            $trainer = $userManager->createUser();
            $trainer->setFirstName($line[0]);
            $trainer->setLastName($line[1]);
            $trainer->setUsername($line[0].' '.$line[1]);
            $trainer->setPlainPassword($line[2]);
            $trainer->setEmail($line[3]);

            $section = $manager
                ->getRepository('OrgabatGameBundle:Section')
                ->findOneByName($line[4])
            ;

            $trainer->addSection($section);
            $trainer->setEnabled(true);
            $trainer->addRole('ROLE_TRAINER');
            $userManager->updateUser($trainer);
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
        $admin->addRole('ROLE_ADMIN');
        $userManager->updateUser($admin);
    }

    public function getOrder()
    {
        return 2;
    }
}
