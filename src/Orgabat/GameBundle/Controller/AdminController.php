<?php

namespace Orgabat\GameBundle\Controller;

use Orgabat\GameBundle\Entity\User;
use Orgabat\GameBundle\Entity\Apprentice;
use Orgabat\GameBundle\Entity\Section;
use Orgabat\GameBundle\Entity\Trainer;
use Orgabat\GameBundle\Form\SectionType;
use Orgabat\GameBundle\Form\TrainerType;
use Orgabat\GameBundle\Form\TrainerUpdateType;
use Orgabat\GameBundle\Form\ApprenticeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormError;

class AdminController extends Controller
{
    /**
     * @ParamConverter("apprentice", options={"mapping": {"apprentice_id": "id"}})
     * @Security("has_role('ROLE_TRAINER')")
     */
    public function viewApprenticeAction(Apprentice $apprentice, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $categories = $em->getRepository('OrgabatGameBundle:Category')->findAll();
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();
        $historyCateg = $em
            ->getRepository('OrgabatGameBundle:Category')
            ->getExercisesOfAllCategoriesByUser($apprentice)
        ;

        $finishedCount = 0;
        $userScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($historyCateg as $category) {
            foreach ($category->getExercises() as $exercise) {
                $best = $exercise->getBestExerciseHistory();

                // Get the total user score
                $userScore['healthNote'] += $best->getHealthNote();
                $userScore['organizationNote'] += $best->getOrganizationNote();
                $userScore['businessNotorietyNote'] += $best->getBusinessNotorietyNote();

                if ($exercise->isFinished()) {
                    ++$finishedCount;
                }
            }
        }

        // Get the total global score
        $globalScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $globalScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }

        return $this->render('OrgabatGameBundle:Admin:userProfile.html.twig', [
            'apprentice' => $apprentice,
            'historyCateg' => $historyCateg,
            'stats' => [
                'user' => $userScore,
                'global' => $globalScore,
            ],
            'minigames' => [
                'finished' => $finishedCount,
                'total' => count($exercises),
            ],
        ]);
    }

    /**
     * @Security("has_role('ROLE_TRAINER')")
     */
    public function createApprenticeAction(Request $request)
    {
        $apprentice = new Apprentice();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ApprenticeType::class, $apprentice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $apprentice = $form->getData();
            $baseUsername = $apprentice->getFirstName(). ' ' . $apprentice->getLastName();
            $apprentice->setUsername($em
                ->getRepository('OrgabatGameBundle:User')
                ->getNotUsedUsername($baseUsername)
            );
            $apprentice->setPlainPassword($apprentice->getBirthDate());
            $apprentice->addRole('ROLE_APPRENTICE');
            $apprentice->setEnabled(true);
            $em->persist($apprentice);
            $em->flush();

            $this->addFlash(
                'message',
                "L'apprenti ".$apprentice->getUsername()." a bien été créé !"
            );

            return $this->redirectToRoute('admin_view_apprentice', [
                'apprentice_id' => $apprentice->getId()
            ]);
        }

        return $this->render('OrgabatGameBundle:Admin:addApprentice.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @ParamConverter("apprentice", options={"mapping": {"apprentice_id": "id"}})
     * @Security("has_role('ROLE_TRAINER')")
     */
    public function editApprenticeAction(Apprentice $apprentice, Request $request)
    {
        $previousFullName = $apprentice->getFullName();
        $form = $this->createForm(ApprenticeType::class, $apprentice);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $apprentice = $form->getData();
            if ($apprentice->getFullName() !== $previousFullName) {
                $apprentice->setUsername($em
                    ->getRepository('OrgabatGameBundle:User')
                    ->getNotUsedUsername($apprentice->getFullName())
                );
            }
            $apprentice->setPlainPassword($apprentice->getBirthDate());
            $em = $this->getDoctrine()->getManager();
            $em->persist($apprentice);
            $em->flush();

            $this->addFlash(
                'message',
                "L'apprenti ".$apprentice->getUsername()." a bien été modifié !"
            );

        }

        return $this->render('OrgabatGameBundle:Admin:editApprentice.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @ParamConverter("user", options={"mapping": {"user_id": "id"}})
     * @Security("has_role('ROLE_TRAINER')")
     */
    public function deleteUserAction(User $user, Request $request)
    {
        $form = $this->createFormBuilder()->getForm();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash(
                'message',
                "L'utilisateur ".$user->getUsername()." a bien été supprimé !"
            );

            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:deleteUser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Exportation des apprentis d'une classe au format CSV => apprentis.csv.
     *
     * @ParamConverter("section", options={"mapping": {"section_id": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportApprenticesBySectionAction(Section $section)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $apprentices = $em
            ->getRepository('OrgabatGameBundle:Apprentice')
            ->findBy(['section' => $section])
        ;
        $handle = fopen('php://memory', 'r+');

        foreach ($apprentices as $apprentice) {
            fputcsv($handle, $apprentice->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, [
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="apprentis.csv"',
        ]);
    }

    /**
     * Exportation de tous les apprentis au format CSV => apprentis.CSV.
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportAllApprenticesAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $apprentices = $em->getRepository('OrgabatGameBundle:Apprentice')->findAll();
        $handle = fopen('php://memory', 'r+');

        foreach ($apprentices as $apprentice) {
            fputcsv($handle, $apprentice->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, [
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="apprentis.csv"',
        ]);
    }

    /*
     * Import apprentices from CSV File
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function importApprenticesAction(Request $request)
    {
        // Form creation
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, ['label' => 'Fichier CSV :'])
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('submitFile');
            $em = $this->getDoctrine()->getManager();

            // Check the file is correct
            if (($handle = fopen($file->getData(), 'r')) !== false) {

                // UserManager for creating a new apprentice
                $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
                $discriminator->setClass('Orgabat\GameBundle\Entity\Apprentice');
                $userManager = $this->container->get('pugx_user_manager');

                // Parsing CSV file using ',' as separator
                while (($data = fgetcsv($handle, ',')) !== false) {
                    for ($i = 0; $i < count($data); $i++) {

                        // Check if the apprentice is not created using unique Email
                        $apprentice = $em
                            ->getRepository('OrgabatGameBundle:Apprentice')
                            ->findOneBy(['email' => $data[3]]);
                        if (!$apprentice) {

                            // Create the apprentice from CSV File datas
                            $apprentice = $userManager->createUser();
                            $apprentice->setFirstName($data[0]);
                            $apprentice->setLastName($data[1]);
                            $baseUsername = $apprentice->getFirstName(). ' ' . $apprentice->getLastName();
                            $apprentice->setUsername($em
                                ->getRepository('OrgabatGameBundle:User')
                                ->getNotUsedUsername($baseUsername)
                            );

                            // Birthdate of the apprentice is used as password
                            $apprentice->setBirthDate($data[2]);
                            $apprentice->setPlainPassword($data[2]);
                            $apprentice->setEmail($data[3]);

                            $section = $em
                                ->getRepository('OrgabatGameBundle:Section')
                                ->findOneBy(['name' => $data[4]]);
                            if ($section != null) {
                                $apprentice->setSection($section);
                            }
                            $apprentice->setEnabled(true);
                            $apprentice->addRole('ROLE_APPRENTICE');
                            $em->persist($apprentice);
                            $em->flush();
                        }
                    }
                }
                fclose($handle);
            }
            $this->addFlash(
                'message',
                'Les apprentis ont bien été importés !'
            );

            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:importFromCsv.html.twig', [
            'form' => $form->createView(),
            'entities' => 'apprentis',
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createTrainerAction(Request $request)
    {
        $trainer = new Trainer();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TrainerType::class, $trainer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trainer = $form->getData();
            $baseUsername = $trainer->getFirstName().' '.$trainer->getLastName();
            $trainer->setUsername($em
                ->getRepository('OrgabatGameBundle:User')
                ->getNotUsedUsername($baseUsername)
            );
            $trainer->addRole('ROLE_TRAINER');
            $trainer->setEnabled(true);
            $em->persist($trainer);
            $em->flush();

            $this->addFlash(
                'message',
                'Le formateur '.$trainer->getUsername().' a bien été créé !'
            );

            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:addTrainer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @ParamConverter("trainer", options={"mapping": {"trainer_id": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editTrainerAction(Trainer $trainer, Request $request)
    {
        $previousFullName = $trainer->getFullName();
        $form = $this->createForm(TrainerUpdateType::class, $trainer);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $trainer = $form->getData();
            $baseUsername = $trainer->getFirstName().' '.$trainer->getLastName();
            if ($trainer->getFullName() !== $previousFullName) {
                $trainer->setUsername($em
                    ->getRepository('OrgabatGameBundle:User')
                    ->getNotUsedUsername($trainer->getFullName())
                );
            }
            $em->persist($trainer);
            $em->flush();

            $this->addFlash(
                'message',
                'Le formateur '.$trainer->getUsername().' a bien été modifié !'
            );

            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:editTrainer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Exportation de tous les formateurs d'une classe au format CSV => formateurs.csv.
     *
     * @ParamConverter("section", options={"mapping": {"section_id": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportTrainersBySectionAction(Section $section)
    {
        $trainers = $this->getDoctrine()->getManager()
            ->getRepository('OrgabatGameBundle:Trainer')
            ->findBy(['section' => $section])
        ;

        $handle = fopen('php://memory', 'r+');

        foreach ($trainers as $trainer) {
            fputcsv($handle, $trainer->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, [
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="formateurs.csv"',
        ]);
    }

    /*
     * Exportation de tous les formateurs au format CSV => formateurs.csv
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportAllTrainersAction()
    {
        $trainers = $this->getDoctrine()->getEntityManager()
            ->getRepository('OrgabatGameBundle:Trainer')
            ->findAll()
        ;
        $handle = fopen('php://memory', 'r+');

        foreach ($trainers as $trainer) {
            fputcsv($handle, $trainer->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, [
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="formateurs.csv"',
        ]);
    }

    /*
     * Importation de plusieurs formateurs depuis un fichier CSV
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function importTrainersAction(Request $request)
    {
        // Form creation
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, ['label' => 'Fichier CSV :'])
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('submitFile');

            $em = $this->getDoctrine()->getManager();
            // Check the file is correct
            if (($handle = fopen($file->getData(), 'r')) !== false) {
                while (($data = fgetcsv($handle, ',')) !== false) {
                    for ($i = 0; $i < count($data); $i++) {

                        // Check if the trainer is not already created
                        $trainer = $em
                            ->getRepository('OrgabatGameBundle:Trainer')
                            ->findOneBy(['email' => $data[2]]);
                        if (!$trainer) {
                            // Create the trainer
                            $trainer = new Trainer();
                            $trainer->setFirstName($data[0]);
                            $trainer->setLastName($data[1]);
                            $baseUsername = $trainer->getFirstName(). ' ' . $trainer->getLastName();
                            $trainer->setUsername($em
                                ->getRepository('OrgabatGameBundle:User')
                                ->getNotUsedUsername($baseUsername)
                            );
                            $trainer->setEmail($data[2]);
                            $trainer->setPassword($data[3]);

                            // Link the trainer with his sections
                            $sectionNames = array_slice($data, 4);
                            foreach ($sectionNames as $sectionName) {
                                $section = $em
                                    ->getRepository('OrgabatGameBundle:Section')
                                    ->findOneBy(['name' => $sectionName])
                                ;
                                if ($section != null) {
                                    $trainer->addSection($section);
                                }
                            }
                            $trainer->setEnabled(true);
                            $trainer->addRole('ROLE_TRAINER');
                            $em->persist($trainer);
                            $em->flush();
                        }
                    }
                }
                fclose($handle);
            }

            $this->addFlash(
                'message',
                'Les formateurs ont bien été importés !'
            );
            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:importFromCsv.html.twig', [
            'form' => $form->createView(),
            'entities' => 'formateurs',
        ]);
    }

    /*
     * Création d'une classe depuis un formulaire à partir de SectionType
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function createSectionAction(Request $request)
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $section = $form->getData();
            $em->persist($section);
            $em->flush();

            $this->addFlash(
                'message',
                'La classe '.$section->getName().' a bien été créée !'
            );

            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:addSection.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Modification d'une classe depuis un formulaire à partir de SectionType.
     *
     * @ParamConverter("section", options={"mapping": {"section_id": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editSectionAction(Section $section, Request $request)
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $section = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();

            $this->addFlash(
                'message',
                'La classe '.$section->getName().' a bien été modifiée !'
            );

            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:editSection.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @ParamConverter("section", options={"mapping": {"section_id": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteSectionAction(Section $section, Request $request)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($section);
            $em->flush();

            $this->addFlash(
                'message',
                'La classe '.$section->getName().' a bien été supprimée !'
            );

            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:deleteSection.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /*
     * Exportation de tous les classes au format CSV => classes.csv
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportAllSectionsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $sections = $em->getRepository('OrgabatGameBundle:Section')->findAll();
        $handle = fopen('php://memory', 'r+');

        foreach ($sections as $section) {
            fputcsv($handle, $section->getData());
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, [
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="classes.csv"',
        ]);
    }

    /*
     * Import sections from a CSV File.
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function importSectionsAction(Request $request)
    {
        // Form creation
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, ['label' => 'Fichier CSV :'])
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm();

        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {
            $file = $form->get('submitFile');

            // Check the file is correct
            if (($handle = fopen($file->getData(), 'r')) !== false) {
                while (($data = fgetcsv($handle, ',')) !== false) {
                    for ($i = 0; $i < count($data); $i++) {

                        // Check if the section is not already created using name
                        $section = $em
                            ->getRepository('OrgabatGameBundle:Section')
                            ->findOneBy(['name' => $data[0]]);
                        if (!$section) {

                            // Create the section
                            $data = explode(',', $data[$i]);
                            $em = $this->getDoctrine()->getManager();
                            $section = new Section();
                            $section->setName($data[0]);
                            $em->persist($section);
                        }
                    }
                }
                fclose($handle);
            }
            $em->flush();

            $this->addFlash(
                'message',
                'Les classes ont bien été importées !'
            );
            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:importFromCsv.html.twig', [
            'form' => $form->createView(),
            'entities' => 'classes',
        ]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function dropDatabaseAction(Request $request)
    {
        $data = [];
        $form = $this->createFormBuilder($data)
            ->add('confirm', TextType::class, [
                'label' => 'Confirmez la suppression des données',
                'attr' => [
                    'placeholder' => 'Veuillez taper "Je confirme"'
                ]
            ])
            ->getForm();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $data = $form->getData();
            if ($data['confirm'] !== "Je confirme") {
                $form->get('confirm')->addError(new FormError('Vous devez taper "Je confirme"'));
            } else {
                $em = $this->getDoctrine()->getManager();
                $commonUsers = $em
                    ->getRepository('OrgabatGameBundle:User')
                    ->getUsersWithoutRole('ROLE_ADMIN')
                ;
                foreach($commonUsers as $user) {
                    $em->remove($user);
                }
                $em->flush();

                $connection = $em->getConnection();
                $platform = $connection->getDatabasePlatform();

                $connection->query('SET FOREIGN_KEY_CHECKS=0');
                $connection->executeUpdate($platform->getTruncateTableSQL('section', true));
                $connection->query('SET FOREIGN_KEY_CHECKS=1');

                $this->addFlash(
                    'message',
                    'La base de données a bien été vidée !'
                );
                return $this->redirectToRoute('default_admin_board');
            }
        }

        return $this->render('OrgabatGameBundle:Admin:dropDatabase.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
