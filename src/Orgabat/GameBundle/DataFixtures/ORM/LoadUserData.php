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
            ['Yves', 'Boulanger', '01012001', 'yves.boulanger@autremail.net', 'BEP 2'],
            ['Lucy', 'Ewing', '17091998', 'venenatis@temporbibendum.co.uk', 'BEP 2' ],
            ['Mercedes', 'Schneider', '14011993', 'tellus.Nunc@molestiesodalesMauris.net', 'BEP 1' ],
            ['Callie', 'Conner', '04071997', 'orci.consectetuer.euismod@velarcu.net', 'CAP 2' ],
            ['Hyacinth', 'Nielsen', '08031994', 'senectus.et.netus@egestas.ca', 'CAP 3' ],
            ['Mary', 'Le', '24071997', 'est@atvelitPellentesque.co.uk', 'CAP 3' ],
            ['Jonah', 'Sims', '19081993', 'Vivamus.rhoncus.Donec@accumsan.ca', 'CAP 2' ],
            ['Merritt', 'Morales', '25101999', 'venenatis.lacus.Etiam@vitaediam.edu', 'CAP 2' ],
            ['Chantale', 'Callahan', '02061995', 'sollicitudin.commodo.ipsum@Crasdictum.com', 'BEP 2' ],
            ['Wyatt', 'Navarro', '19091996', 'ipsum@morbitristique.edu', 'BEP 2' ],
            ['Lydia', 'Parsons', '16031996', 'at.arcu.Vestibulum@utquam.net', 'CAP 2' ],
            ['Echo', 'Strickland', '25081997', 'Quisque.fringilla.euismod@sedconsequatauctor.com', 'CAP 1' ],
            ['Ignatius', 'Erickson', '27091994', 'Nunc.commodo.auctor@dolor.org', 'BEP 3' ],
            ['Benjamin', 'Vargas', '24101997', 'Nullam@iaculisodioNam.org', 'CAP 1' ],
            ['Melinda', 'Campbell', '01021999', 'ipsum@nuncnulla.edu', 'BEP 1' ],
            ['Chiquita', 'Knapp', '09061999', 'elit.pellentesque.a@cursus.net', 'CAP 1' ],
            ['Amena', 'Brewer', '18041997', 'aliquam.enim@risusIn.co.uk', 'CAP 1' ],
            ['Xerxes', 'Burke', '09012000', 'eu.nibh@eudolor.edu', 'BEP 3' ],
            ['Zeus', 'Duffy', '15021998', 'pede.ultrices.a@ac.org', 'BEP 3' ],
            ['Mikayla', 'Slater', '12061993', 'ante@tristiquesenectuset.edu', 'BEP 1' ],
            ['Rhonda', 'Miller', '13031998', 'mollis.Integer.tincidunt@sitamet.ca', 'CAP 3' ],
            ['Jescie', 'Stein', '12031994', 'dignissim.lacus@dolorDonecfringilla.org', 'CAP 1' ],
            ['Shay', 'Clark', '11021995', 'orci@lectuspedeet.ca', 'CAP 2' ],
            ['Hilda', 'Bass', '28021997', 'velit.Quisque.varius@varius.ca', 'CAP 2' ],
            ['Hall', 'Small', '19111994', 'porttitor.scelerisque.neque@estmaurisrhoncus.co.uk', 'BEP 1' ],
            ['Charissa', 'Ryan', '07012000', 'posuere.cubilia@adipiscing.net', 'CAP 2' ],
            ['Amery', 'Osborn', '14011995', 'in@diam.edu', 'CAP 1' ],
            ['Ezekiel', 'Sosa', '23101998', 'amet.orci.Ut@Proinsedturpis.com', 'BEP 3' ],
            ['Charity', 'Mccormick', '13021997', 'Sed@id.ca', 'BEP 1' ],
            ['Karleigh', 'Alvarado', '14121994', 'Mauris@sapien.edu', 'CAP 2' ],
            ['Arden', 'Fuentes', '15051999', 'mauris.eu@eu.net', 'CAP 2' ],
            ['Benjamin', 'Fuentes', '22071997', 'ligula.Aenean@atarcuVestibulum.ca', 'BEP 1' ],
            ['Illana', 'Nieves', '05111999', 'Etiam.laoreet@dictumeu.org', 'CAP 1' ],
            ['Mark', 'Beard', '08111995', 'turpis@scelerisque.edu', 'CAP 2' ],
            ['Mikayla', 'Klein', '12101994', 'sapien.molestie@mauris.org', 'CAP 2' ],
            ['Desiree', 'Larsen', '04041997', 'per.inceptos@magnaLorem.net', 'BEP 2' ],
            ['Macon', 'Clements', '20111997', 'dignissim.Maecenas@etmalesuada.edu', 'CAP 3' ],
            ['Guy', 'Cline', '03061994', 'in.tempus.eu@etnetus.co.uk', 'CAP 3' ],
            ['Jakeem', 'Herman', '30121997', 'nascetur.ridiculus@aliquet.edu', 'BEP 1' ],
            ['Daphne', 'Wooten', '08061997', 'Fusce.aliquet.magna@Aliquamgravidamauris.org', 'CAP 3' ],
            ['Susan', 'Nash', '21021995', 'consectetuer@nunc.org', 'CAP 1' ],
            ['Elliott', 'Waters', '15011995', 'pede@semsemper.edu', 'BEP 1' ],
            ['Alfreda', 'Gomez', '02011999', 'ipsum@euismodenim.org', 'CAP 3' ],
            ['Wang', 'Joyce', '06041998', 'ac@auctor.org', 'BEP 2' ],
            ['Sierra', 'Perry', '08121996', 'sit.amet.faucibus@noncursus.ca', 'BEP 3' ],
            ['Clinton', 'Wilkinson', '14121996', 'nec@amet.co.uk', 'CAP 1' ],
            ['Kameko', 'Snider', '25101993', 'eget.volutpat@Mauris.co.uk', 'BEP 2' ],
            ['Colt', 'George', '18021994', 'malesuada.Integer@antebibendum.ca', 'CAP 3' ],
            ['Sylvia', 'Ellis', '27051997', 'at@Curabiturutodio.edu', 'CAP 3' ],
            ['Herrod', 'Hammond', '29101995', 'ligula.tortor.dictum@auctorquis.org', 'CAP 2' ],
            ['Ulysses', 'Fitzgerald', '17061996', 'dui@Praesenteu.co.uk', 'CAP 3' ],
            ['Patricia', 'Scott', '11101993', 'Etiam@malesuadafames.com', 'CAP 2' ],
            ['Cynthia', 'Savage', '07121994', 'aliquam@elementum.net', 'CAP 2' ],
            ['Freya', 'Wade', '28071997', 'condimentum.Donec.at@congue.net', 'BEP 2' ],
            ['Gannon', 'Olsen', '18081999', 'auctor.ullamcorper.nisl@nonhendrerit.co.uk', 'CAP 3' ],
            ['Noble', 'Valenzuela', '30051999', 'eros.nec@mus.co.uk', 'BEP 3' ],
            ['Lois', 'Petersen', '26071996', 'ornare.libero.at@eleifendvitaeerat.edu', 'CAP 3' ],
            ['Zephr', 'Mcleod', '27031997', 'semper.erat@lorem.net', 'CAP 3' ],
            ['Jarrod', 'Ellison', '10051998', 'metus@enim.org', 'CAP 3' ],
            ['Francis', 'Robinson', '13121996', 'posuere.cubilia.Curae@Nullam.edu', 'CAP 2' ],
            ['Galvin', 'Thompson', '24061995', 'sem.egestas@elitpede.co.uk', 'CAP 3' ],
            ['Perry', 'Medina', '04031994', 'orci.sem.eget@neceuismod.org', 'CAP 2' ],
            ['Azalia', 'Ray', '25051995', 'non.quam@eleifendegestas.net', 'BEP 1' ],
            ['Uta', 'Rutledge', '11031995', 'penatibus.et@magnaPhasellus.org', 'CAP 1' ],
            ['Alexis', 'Mills', '01031996', 'quam.a@nunc.edu', 'CAP 3' ],
            ['Kerry', 'Salazar', '19041997', 'nec.tellus@Maecenas.com', 'BEP 3' ],
            ['Yael', 'Booker', '13101999', 'mattis@consequatdolor.ca', 'CAP 3' ],
            ['Myra', 'Valenzuela', '04041997', 'Cum.sociis@anteVivamusnon.co.uk', 'CAP 2' ],
            ['Quynn', 'Patel', '05071996', 'sagittis@ipsumSuspendisse.co.uk', 'CAP 2' ],
            ['Graiden', 'Jensen', '05051995', 'lacus.Aliquam.rutrum@Proineget.co.uk', 'BEP 3' ],
            ['Sopoline', 'Oconnor', '19071996', 'amet.risus.Donec@nislMaecenasmalesuada.com', 'CAP 3' ],
            ['Cheryl', 'Holden', '24031998', 'lobortis@lacus.ca', 'CAP 3' ],
            ['Maya', 'Moody', '18021999', 'scelerisque.sed.sapien@porttitortellus.co.uk', 'BEP 1' ],
            ['Meghan', 'Delaney', '02041996', 'quis@massarutrum.net', 'BEP 3' ],
            ['Justine', 'Camacho', '28071995', 'luctus.ut.pellentesque@semvitae.net', 'CAP 1' ],
            ['Aline', 'Pierce', '13011996', 'a.sollicitudin.orci@arcu.com', 'CAP 2' ],
            ['Rogan', 'Melton', '01031993', 'In.tincidunt.congue@diam.org', 'BEP 1' ],
            ['Kristen', 'Huffman', '11121998', 'nisi@seddictum.net', 'BEP 1' ],
            ['Charity', 'Noble', '29081994', 'accumsan.interdum@commodohendrerit.ca', 'BEP 2' ],
            ['Roanna', 'Walton', '02031998', 'nisl@dapibusgravida.edu', 'CAP 1' ],
            ['Sigourney', 'Salinas', '03021993', 'elit.Curabitur@Inscelerisquescelerisque.co.uk', 'CAP 3' ],
            ['Yuri', 'Hess', '30121999', 'metus.Vivamus@elit.ca', 'CAP 2' ],
            ['Jeremy', 'Knight', '12091994', 'at.velit@laciniaat.co.uk', 'BEP 3' ],
            ['Micah', 'Francis', '29091994', 'pharetra@consectetuer.edu', 'BEP 3' ],
            ['Brenna', 'Sweet', '08011995', 'sed.pede.Cum@id.edu', 'BEP 2' ],
            ['Olivia', 'Morris', '11121994', 'malesuada@Nunc.net', 'BEP 1' ],
            ['Zena', 'Woodard', '01101999', 'Donec.tempus@euneque.co.uk', 'BEP 1' ],
            ['Rebecca', 'Spence', '19121996', 'elit@tempusnon.edu', 'BEP 1' ],
            ['Carol', 'Roman', '06011998', 'sollicitudin.orci@ligula.com', 'CAP 2' ],
            ['Demetrius', 'Delaney', '24041998', 'placerat@vitaesodales.ca', 'BEP 1' ],
            ['Jennifer', 'Gutierrez', '19121999', 'hendrerit.a.arcu@Nullamenim.co.uk', 'BEP 1' ],
            ['Celeste', 'Melton', '01031998', 'accumsan@lacusQuisqueimperdiet.org', 'BEP 3' ],
            ['Raven', 'Coleman', '30061993', 'Duis.elementum@nonfeugiatnec.ca', 'CAP 2' ],
            ['Jeanette', 'Kent', '20051997', 'est.ac@auctor.org', 'CAP 2' ],
            ['Axel', 'Molina', '14051999', 'quis@sapienmolestieorci.org', 'BEP 3' ],
            ['Tallulah', 'Stark', '22091996', 'dapibus@Crassed.org', 'CAP 3' ],
            ['Harlan', 'Finley', '22121994', 'sit.amet.consectetuer@porttitor.net', 'CAP 1' ],
            ['Lewis', 'Brady', '01101993', 'adipiscing@eros.ca', 'BEP 3' ],
            ['Aphrodite', 'Horn', '08081994', 'elementum@quisturpis.edu', 'CAP 2' ],
            ['Levi', 'Walter', '29081998', 'mauris@Phaselluselit.edu', 'CAP 3' ],
            ['Beatrice', 'Mills', '04031996', 'cursus@rutrumjustoPraesent.com', 'BEP 2' ]
        ];

        // First Name, Last Name, Password, Email, Section(s)
        $trainerData = [
            ['Prof', 'Test', '12345678', 'prof.test@mail.com', [ 'CAP 1', 'BEP 2']],
            ['Autre', 'Prof', '12345678', 'autre.prof@mail.fr', [ 'BEP 2']],
            [ 'Chancellor', 'Compton', '02111994', 'turpis.Nulla.aliquet@sed.net', [ 'BEP 3', 'CAP 3', 'CAP 1', 'BEP 1' ]],
            [ 'Yvonne', 'Craig', '06081995', 'eget.odio@Nuncmauriselit.co.uk', [ 'CAP 1', 'CAP 2', 'BEP 2', 'BEP 1' ]],
            [ 'Sasha', 'Bowen', '05041998', 'lorem.ac.risus@dolorsitamet.com', [ 'CAP 2' ]],
            [ 'Drake', 'Brennan', '15011998', 'mauris.a@enim.net', [ 'BEP 1' ]],
            [ 'Carson', 'Clayton', '21091995', 'eu.accumsan.sed@cubiliaCurae.net', [ 'BEP 1' ]]
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
            $userManager->updateUser($apprentice, false);
        }

        $discriminator->setClass('Orgabat\GameBundle\Entity\Trainer');

        foreach ($trainerData as $line) {

            $trainer = $userManager->createUser();
            $trainer->setFirstName($line[0]);
            $trainer->setLastName($line[1]);
            $trainer->setUsername($line[0].' '.$line[1]);
            $trainer->setPlainPassword($line[2]);
            $trainer->setEmail($line[3]);

            foreach( $line[4] as $section) {
                $result = $manager
                    ->getRepository('OrgabatGameBundle:Section')
                    ->findOneByName($section)
                ;
                $trainer->addSection($result);
            }
            $trainer->setEnabled(true);
            $trainer->addRole('ROLE_TRAINER');
            $userManager->updateUser($trainer, false);
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
        $userManager->updateUser($admin, false);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
