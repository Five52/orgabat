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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

            $request->getSession()->getFlashBag()->add(
                'message',
                "L'apprenti ".$apprentice->getUsername().' a bien été créé !'
            );

            return $this->redirectToRoute('default_admin_board');
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
        $form = $this->createForm(ApprenticeType::class, $apprentice);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $apprentice = $form->getData();
            $baseUsername = $apprentice->getFirstName().' '.$apprentice->getLastName();
            $apprentice->setUsername($em
                ->getRepository('OrgabatGameBundle:User')
                ->getNotUsedUsername($baseUsername)
            );
            $apprentice->setPlainPassword($apprentice->getBirthDate());
            $em = $this->getDoctrine()->getManager();
            $em->persist($apprentice);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'message',
                "L'apprenti ".$apprentice->getUsername().' a bien été modifié !'
            );

            return $this->redirectToRoute('default_admin_board');
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

            $request->getSession()->getFlashBag()->add(
                'message',
                "L'utilisateur ".$user->getUsername().' a bien été supprimé !'
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

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="apprentis.csv"',
        ));
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

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="apprentis.csv"',
        ));
    }

    /*
     * Importation de plusieurs apprentis depuis un fichier CSV
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function importApprenticesAction(Request $request)
    {
        // Création du formulaire
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, array('label' => 'Fichier CSV :'))
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('submitFile');
            // On vérifie la validité du fichier
            if (($handle = fopen($file->getData(), 'r')) !== false) {
                $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
                $discriminator->setClass('Orgabat\GameBundle\Entity\Apprentice');
                $userManager = $this->container->get('pugx_user_manager');

                while (($data = fgetcsv($handle, ',')) !== false) {
                    $num = count($data);
                    for ($c = 0; $c < $num; ++$c) {
                        $em = $this->getDoctrine()->getManager();
                        // On vérifie que l'apprenti n'est pas déjà créé
                        $apprentice = $em
                            ->getRepository('OrgabatGameBundle:Apprentice')
                            ->findOneBy(array('firstName' => $data[0]));
                        if (!$apprentice) {
                            // Création de l'apprenti à partir des données CSV
                            $apprentice = $userManager->createUser();
                            $apprentice->setFirstName($data[0]);
                            $apprentice->setLastName($data[1]);
                            $apprentice->setUsername($data[0].' '.$data[1]);
                            // La date de naissance de l'apprenti est utilisé
                            // comme mot de passe
                            $apprentice->setBirthDate($data[2]);
                            $apprentice->setPlainPassword($data[2]);
                            $apprentice->setEmail($data[3]);

                            $section = $em
                                ->getRepository('OrgabatGameBundle:Section')
                                ->findOneBy(array('name' => $data[4]));
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
            $request->getSession()->getFlashBag()->add(
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

            $request->getSession()->getFlashBag()->add(
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
        $form = $this->createForm(TrainerUpdateType::class, $trainer);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $trainer = $form->getData();
            $baseUsername = $trainer->getFirstName().' '.$trainer->getLastName();
            $trainer->setUsername($em
                ->getRepository('OrgabatGameBundle:User')
                ->getNotUsedUsername($baseUsername)
            );
            $em->persist($trainer);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'message',
                "Le formateur ".$trainer->getUsername().' a bien été modifié !'
            );

            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:editTrainer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Exportation de tous les enseignants d'une classe au format CSV => enseignants.csv.
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

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="enseignants.csv"',
        ));
    }

    /*
     * Exportation de tous les enseignants au format CSV => enseignants.csv
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
            'Content-Disposition' => 'attachment; filename="enseignants.csv"',
        ]);
    }

    /*
     * Importation de plusieurs enseignants depuis un fichier CSV
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function importTrainersAction(Request $request)
    {
        // Création du formulaire d'importation du fichier
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, ['label' => 'Fichier CSV :'])
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('submitFile');
            // On vérifie que le fichier CSV est valide
            if (($handle = fopen($file->getData(), 'r')) !== false) {
                while (($data = fgetcsv($handle, ',')) !== false) {
                    $num = count($data);
                    for ($c = 0; $c < $num; ++$c) {
                        // On vérifie que l'enseignant n'existe pas déjà
                        $em = $this->getDoctrine()->getManager();
                        $trainer = $em
                            ->getRepository('OrgabatGameBundle:Trainer')
                            ->findOneBy(array('firstName' => $data[0]));
                        if (!$trainer) {
                            // Création de l'enseignant
                            $trainer = new Trainer();
                            $trainer->setFirstName($data[0]);
                            $trainer->setLastName($data[1]);
                            $trainer->setUsername($data[0].' '.$data[1]);
                            $trainer->setEmail($data[2]);
                            $trainer->setPassword($data[3]);

                            $sectionNames = array_slice($data, 4);
                            foreach ($sectionNames as $sectionName) {
                                $section = $em
                                    ->getRepository('OrgabatGameBundle:Section')
                                    ->findOneBy(['name' => $sectionName])
                                ;
                                $trainer->addSection($section);
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

            $request->getSession()->getFlashBag()->add(
                'message',
                'Les formateurs ont bien été importés !'
            );
            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:importFromCsv.html.twig', [
            'form' => $form->createView(),
            'entities' => 'enseignants',
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

            $request->getSession()->getFlashBag()->add(
                'message',
                'La section '.$section->getName().' a bien été créée !'
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

            $request->getSession()->getFlashBag()->add(
                'message',
                'La section '.$section->getName().' a bien été modifiée !'
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

            $request->getSession()->getFlashBag()->add(
                'message',
                'La section '.$section->getName().' a bien été supprimée !'
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

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="classes.csv"',
        ));
    }

    /*
     * Importation de plusieurs classes depuis un fichier CSV
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function importSectionsAction(Request $request)
    {
        // Création du formulaire
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, array('label' => 'Fichier CSV :'))
            ->add('save', SubmitType::class, ['label' => 'Importer'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $file = $form->get('submitFile');
            // On vérifie que le fichier est valide
            if (($handle = fopen($file->getData(), 'r')) !== false) {
                while (($data = fgetcsv($handle, ',')) !== false) {
                    $num = count($data);
                    for ($c = 0; $c < $num; ++$c) {
                        // Création de la classe
                        $data = explode(',', $data[$c]);
                        $em = $this->getDoctrine()->getManager();
                        $section = new Section();
                        $section->setName($data[0]);
                        $em->persist($section);
                        $em->flush();
                    }
                }
                fclose($handle);
            }

            $request->getSession()->getFlashBag()->add(
                'message',
                'Les sections ont bien été importées !'
            );
            return $this->redirectToRoute('default_admin_board');
        }

        return $this->render('OrgabatGameBundle:Admin:importFromCsv.html.twig', [
            'form' => $form->createView(),
            'entities' => 'sections',
        ]);
    }
}
