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
            [30, 1, 1, 7, 'Jeu 3', 'Jean Duval'],
            [ 140, 5, 3, 8, 'Jeu 9', 'Myra Valenzuela' ],
            [ 188, 9, 2, 4, 'Jeu 7', 'Hyacinth Nielsen' ],
            [ 204, 2, 4, 8, 'Jeu 20', 'Echo Strickland' ],
            [ 139, 0, 8, 6, 'Jeu 11', 'Galvin Thompson' ],
            [ 291, 6, 9, 0, 'Jeu 20', 'Jeremy Knight' ],
            [ 137, 1, 3, 0, 'Jeu 7', 'Rhonda Miller' ],
            [ 251, 7, 6, 2, 'Jeu 7', 'Charissa Ryan' ],
            [ 249, 9, 2, 1, 'Jeu 15', 'Charissa Ryan' ],
            [ 262, 6, 7, 1, 'Jeu 23', 'Myra Valenzuela' ],
            [ 243, 7, 8, 9, 'Jeu 24', 'Elliott Waters' ],
            [ 124, 5, 1, 8, 'Jeu 13', 'Raven Coleman' ],
            [ 222, 9, 0, 7, 'Jeu 14', 'Gannon Olsen' ],
            [ 152, 0, 3, 1, 'Jeu 10', 'Carol Roman' ],
            [ 192, 0, 3, 3, 'Jeu 7', 'Amery Osborn' ],
            [ 130, 6, 8, 4, 'Jeu 18', 'Harlan Finley' ],
            [ 225, 8, 3, 8, 'Jeu 5', 'Ulysses Fitzgerald' ],
            [ 121, 2, 5, 5, 'Jeu 11', 'Uta Rutledge' ],
            [ 125, 5, 8, 5, 'Jeu 7', 'Echo Strickland' ],
            [ 186, 7, 1, 2, 'Jeu 23', 'Tallulah Stark' ],
            [ 252, 2, 8, 3, 'Jeu 7', 'Kerry Salazar' ],
            [ 133, 7, 6, 5, 'Jeu 19', 'Jacques Dupont' ],
            [ 277, 1, 9, 8, 'Jeu 3', 'Francis Robinson' ],
            [ 268, 2, 6, 6, 'Jeu 11', 'Yael Booker' ],
            [ 139, 1, 0, 0, 'Jeu 10', 'Benjamin Vargas' ],
            [ 232, 8, 6, 7, 'Jeu 3', 'Chiquita Knapp' ],
            [ 241, 4, 1, 6, 'Jeu 12', 'Colt George' ],
            [ 244, 6, 0, 6, 'Jeu 7', 'Alexis Mills' ],
            [ 268, 8, 4, 6, 'Jeu 7', 'Sierra Perry' ],
            [ 211, 8, 5, 1, 'Jeu 8', 'Gannon Olsen' ],
            [ 126, 1, 2, 4, 'Jeu 21', 'Carol Roman' ],
            [ 296, 7, 6, 1, 'Jeu 10', 'Melinda Campbell' ],
            [ 268, 3, 3, 4, 'Jeu 19', 'Maya Moody' ],
            [ 143, 6, 2, 8, 'Jeu 11', 'Charissa Ryan' ],
            [ 282, 0, 1, 2, 'Jeu 8', 'Gannon Olsen' ],
            [ 179, 1, 1, 4, 'Jeu 24', 'Chantale Callahan' ],
            [ 278, 9, 6, 5, 'Jeu 12', 'Charissa Ryan' ],
            [ 170, 9, 9, 1, 'Jeu 16', 'Benjamin Vargas' ],
            [ 216, 1, 1, 7, 'Jeu 21', 'Lydia Parsons' ],
            [ 174, 0, 2, 1, 'Jeu 17', 'Patricia Scott' ],
            [ 211, 2, 7, 2, 'Jeu 14', 'Carol Roman' ],
            [ 185, 2, 6, 5, 'Jeu 11', 'Zephr Mcleod' ],
            [ 123, 2, 8, 3, 'Jeu 3', 'Kerry Salazar' ],
            [ 186, 0, 0, 7, 'Jeu 20', 'Sigourney Salinas' ],
            [ 262, 2, 7, 6, 'Jeu 20', 'Shay Clark' ],
            [ 218, 8, 1, 0, 'Jeu 11', 'Karleigh Alvarado' ],
            [ 221, 5, 7, 3, 'Jeu 21', 'Zephr Mcleod' ],
            [ 209, 5, 3, 1, 'Jeu 14', 'Celeste Melton' ],
            [ 162, 4, 1, 6, 'Jeu 12', 'Zeus Duffy' ],
            [ 300, 4, 3, 2, 'Jeu 22', 'Hyacinth Nielsen' ],
            [ 283, 1, 6, 0, 'Jeu 6', 'Wyatt Navarro' ],
            [ 294, 9, 1, 4, 'Jeu 9', 'Myra Valenzuela' ],
            [ 159, 8, 0, 2, 'Jeu 20', 'Raven Coleman' ],
            [ 149, 0, 2, 8, 'Jeu 10', 'Micah Francis' ],
            [ 224, 6, 4, 4, 'Jeu 20', 'Uta Rutledge' ],
            [ 171, 5, 1, 8, 'Jeu 21', 'Gannon Olsen' ],
            [ 280, 0, 5, 6, 'Jeu 20', 'Rebecca Spence' ],
            [ 146, 2, 9, 0, 'Jeu 15', 'Hyacinth Nielsen' ],
            [ 198, 6, 6, 7, 'Jeu 21', 'Rebecca Spence' ],
            [ 184, 9, 4, 0, 'Jeu 12', 'Tallulah Stark' ],
            [ 192, 6, 5, 0, 'Jeu 18', 'Kristen Huffman' ],
            [ 213, 7, 2, 3, 'Jeu 10', 'Echo Strickland' ],
            [ 226, 2, 2, 8, 'Jeu 4', 'Jacques Dupont' ],
            [ 222, 9, 1, 9, 'Jeu 6', 'Demetrius Delaney' ],
            [ 299, 4, 8, 0, 'Jeu 4', 'Mikayla Klein' ],
            [ 182, 8, 7, 7, 'Jeu 23', 'Alfreda Gomez' ],
            [ 233, 8, 9, 6, 'Jeu 7', 'Desiree Larsen' ],
            [ 269, 5, 7, 7, 'Jeu 4', 'Ignatius Erickson' ],
            [ 170, 9, 6, 0, 'Jeu 14', 'Benjamin Vargas' ],
            [ 300, 8, 0, 0, 'Jeu 8', 'Macon Clements' ],
            [ 247, 6, 5, 8, 'Jeu 15', 'Callie Conner' ],
            [ 202, 3, 3, 7, 'Jeu 5', 'Mary Le' ],
            [ 206, 5, 2, 2, 'Jeu 12', 'Amery Osborn' ],
            [ 164, 7, 4, 5, 'Jeu 5', 'Illana Nieves' ],
            [ 173, 3, 9, 1, 'Jeu 17', 'Kristen Huffman' ],
            [ 298, 8, 7, 3, 'Jeu 6', 'Celeste Melton' ],
            [ 166, 8, 3, 8, 'Jeu 11', 'Rhonda Miller' ],
            [ 228, 0, 0, 3, 'Jeu 6', 'Freya Wade' ],
            [ 171, 8, 5, 3, 'Jeu 9', 'Hall Small' ],
            [ 233, 6, 9, 6, 'Jeu 12', 'Illana Nieves' ],
            [ 199, 9, 9, 4, 'Jeu 1', 'Callie Conner' ],
            [ 258, 5, 9, 7, 'Jeu 5', 'Zena Woodard' ],
            [ 219, 7, 6, 1, 'Jeu 7', 'Lois Petersen' ],
            [ 257, 1, 0, 0, 'Jeu 11', 'Jescie Stein' ],
            [ 211, 2, 3, 6, 'Jeu 5', 'Beatrice Mills' ],
            [ 174, 7, 6, 0, 'Jeu 15', 'Harlan Finley' ],
            [ 159, 6, 0, 4, 'Jeu 3', 'Clinton Wilkinson' ],
            [ 264, 0, 9, 8, 'Jeu 21', 'Patricia Scott' ],
            [ 204, 9, 4, 9, 'Jeu 2', 'Ulysses Fitzgerald' ],
            [ 183, 7, 6, 9, 'Jeu 5', 'Jennifer Gutierrez' ],
            [ 120, 0, 4, 1, 'Jeu 9', 'Macon Clements' ],
            [ 239, 9, 1, 3, 'Jeu 14', 'Patricia Scott' ],
            [ 276, 1, 1, 5, 'Jeu 12', 'Celeste Melton' ],
            [ 216, 8, 3, 4, 'Jeu 3', 'Desiree Larsen' ],
            [ 170, 7, 8, 5, 'Jeu 5', 'Lydia Parsons' ],
            [ 122, 3, 4, 8, 'Jeu 14', 'Ulysses Fitzgerald' ],
            [ 132, 0, 7, 3, 'Jeu 1', 'Meghan Delaney' ],
            [ 125, 0, 0, 9, 'Jeu 5', 'Jonah Sims' ],
            [ 157, 3, 1, 1, 'Jeu 12', 'Herrod Hammond' ],
            [ 241, 6, 8, 0, 'Jeu 11', 'Susan Nash' ],
            [ 246, 2, 4, 7, 'Jeu 20', 'Jescie Stein' ],
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
