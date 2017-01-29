<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Orgabat\GameBundle\Form\TrainerSelfUpdateType;
use Orgabat\GameBundle\Form\AdminUpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Orgabat\GameBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function showRulesAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_regles.html.twig');
    }

    public function showConnectionFormUserAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_co.html.twig');
    }

    public function showMenuAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $categories = $em->getRepository('OrgabatGameBundle:Category')->findAll();
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();
        $historyCateg = $em
            ->getRepository('OrgabatGameBundle:Category')
            ->getExercisesOfAllCategoriesByUser($user)
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

        return $this->render('OrgabatGameBundle:User:page_rubriques.html.twig', [
            'categories' => $categories,
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
     * @ParamConverter("category", options={"mapping": {"category_id": "id"}})
     */
    public function showGamesAction(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $exercises = $em
            ->getRepository('OrgabatGameBundle:Exercise')
            ->getExercisesOfCategoryWithUserInfos($category, $user)
        ;

        // STATS
        $userCategScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        $globalCategScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $best = $exercise->getBestExerciseHistory();
            if (!is_null($best)) {
                // Get the total user score
                $userCategScore['healthNote'] += $best->getHealthNote();
                $userCategScore['organizationNote'] += $best->getOrganizationNote();
                $userCategScore['businessNotorietyNote'] += $best->getBusinessNotorietyNote();
            }
            // Get the total global score
            $globalCategScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalCategScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalCategScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }

        return $this->render('OrgabatGameBundle:User:page_jeux.html.twig', [
            'category' => $category,
            'exercises' => $exercises,
            'bestTries' => [],
            'categStats' => [
                'user' => $userCategScore,
                'global' => $globalCategScore,
            ],
            'tryCount' => [],
        ]);
    }

    // Dashboard principal de l'administrateur
    public function showAdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sections = $em
            ->getRepository('OrgabatGameBundle:Section')
            //->findBy([], ['id' => 'asc'])
            ->getWithTrainersAndApprentices()
        ;
        $apprenticesWithoutSection = $em
            ->getRepository('OrgabatGameBundle:Apprentice')
            ->findBy(['section' => null])
        ;
        $trainersWithoutSection = $em
            ->getRepository('OrgabatGameBundle:Trainer')
            ->getWithoutSections()
        ;

        return $this->render('OrgabatGameBundle:Admin:page_dashboard.html.twig', [
            'sections' => $sections,
            'apprenticesWithoutSection' => $apprenticesWithoutSection,
            'trainersWithoutSection' => $trainersWithoutSection
        ]);
    }

    // Liste des classes
    public function showSectionsAction()
    {
        // On récupère les informations de l'utilisateur du site
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // S'il s'agit d'un admin on retourne tous les classes avec tous les apprentis
            $sections = $em
                ->getRepository('OrgabatGameBundle:Section')
                //->findBy([], ['id' => 'asc'])
                ->getWithTrainersAndApprentices()
            ;
        }else {
            // S'il s'agit d'un enseignant, on retoune la classe qu'il anime
            $trainerRepository = $this->getDoctrine()->getRepository('OrgabatGameBundle:Trainer');
            $sections = $trainerRepository->getWithSections($user->getId())[0]->getSections();
            ;
        }

        return $this->render('OrgabatGameBundle:Admin:showSections.html.twig', [
            "sections" => $sections
        ]);

    }

    public function showEditInfosAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(AdminUpdateType::class, $user);
        } else {
            $form = $this->createForm(TrainerSelfUpdateType::class, $user);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // Update username
            $user->setUsername($user->getFirstName().' '.$user->getLastName());

            // Update password
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $newpassword = $encoder->encodePassword(
                $user->getPlainPassword(),
                $user->getSalt()
            );
            $user->setPassword($newpassword);

            // Update user
            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();

            // TODO: Update link to the home page
            return $this->redirectToRoute('default_sections');
        }

        return $this->render('OrgabatGameBundle:Admin:showEditInfos.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function getUserInfosUsingPDFAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();
        $categories = $em
            ->getRepository('OrgabatGameBundle:Category')
            ->getExercisesOfAllCategoriesByUser($user)
        ;
        $userScore = [];
        foreach ($categories as $category) {
            foreach ($category->getExercises() as $exercise) {
                $userScore[$category->getName()] = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
                $best = $exercise->getBestExerciseHistory();

                // Get the total user score
                $userScore[$category->getName()]['timer'] = $best->getTimer();
                $userScore[$category->getName()]['healthNote'] = $best->getHealthNote();
                $userScore[$category->getName()]['organizationNote'] = $best->getOrganizationNote();
                $userScore[$category->getName()]['businessNotorietyNote'] = $best->getBusinessNotorietyNote();
            }
        }

        // Get the total global score
        $globalScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $globalScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }
        $html = $this->renderView('OrgabatGameBundle:Pdf:user.html.twig', array(
            'user' => $user,
            'categories' => $categories,
            'stats' => [
                'global' => $globalScore
            ],
        ));/*
        return $this->render('OrgabatGameBundle:Pdf:user.html.twig', array(
            'user' => $user,
            'categories' => $categories,
            'stats' => [
                'global' => $globalScore
            ],
        ));*/
        $html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response(
            $html2pdf->Output(__DIR__.$user->getName().".pdf", 'S'),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="' . $user->getName() . '".pdf"'
            )
        );
    }

    public function getAllUsersInfosUsingPDFAction() {
        $em = $this->getDoctrine()->getManager();
        $apprentices = $em
            ->getRepository('OrgabatGameBundle:Apprentice')
            ->findAll()
        ;
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();
        $categoriesOfAllUsers = [];
        foreach ($apprentices as $apprentice) {
            $categories = $em
                ->getRepository('OrgabatGameBundle:Category')
                ->getExercisesOfAllCategoriesByUser($apprentice)
            ;
            $userScore = [];
            foreach ($categories as $category) {
                foreach ($category->getExercises() as $exercise) {
                    $userScore[$category->getName()] = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
                    $best = $exercise->getBestExerciseHistory();

                    // Get the total user score
                    $userScore[$category->getName()]['timer'] = $best->getTimer();
                    $userScore[$category->getName()]['healthNote'] = $best->getHealthNote();
                    $userScore[$category->getName()]['organizationNote'] = $best->getOrganizationNote();
                    $userScore[$category->getName()]['businessNotorietyNote'] = $best->getBusinessNotorietyNote();
                }
            }
            $categoriesOfAllUsers[] = $categories;
        }
        // Get the total global score
        $globalScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $globalScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }
        /*return $this->render('OrgabatGameBundle:Pdf:allUsers.html.twig', array(
            'users' => $users,
            'categories' => $categoriesOfAllUsers,
            'stats' => [
                'global' => $globalScore
            ],
        ));*/
        $html = $this->renderView('OrgabatGameBundle:Pdf:allUsers.html.twig', array(
            'users' => $apprentices,
            'categories' => $categoriesOfAllUsers,
            'stats' => [
                'global' => $globalScore
            ],
        ));
        $html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response(
            $html2pdf->Output(__DIR__."apprentis.pdf", 'S'),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="apprentis.pdf"'
            )
        );

    }
    public function getUsersInfosUsingPDFBySectionAction() {
        $em = $this->getDoctrine()->getManager();
        $users = $em
            ->getRepository('OrgabatGameBundle:Apprentice')
            ->findAll()
        ;
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->findAll();
        $categoriesOfAllUsers = [];
        foreach ($users as $user) {
            $categories = $em
                ->getRepository('OrgabatGameBundle:Category')
                ->getExercisesOfAllCategoriesByUser($user)
            ;
            $userScore = [];
            foreach ($categories as $category) {
                foreach ($category->getExercises() as $exercise) {
                    $userScore[$category->getName()] = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
                    $best = $exercise->getBestExerciseHistory();

                    // Get the total user score
                    $userScore[$category->getName()]['timer'] = $best->getTimer();
                    $userScore[$category->getName()]['healthNote'] = $best->getHealthNote();
                    $userScore[$category->getName()]['organizationNote'] = $best->getOrganizationNote();
                    $userScore[$category->getName()]['businessNotorietyNote'] = $best->getBusinessNotorietyNote();
                }
            }
            $categoriesOfAllUsers[] = $categories;
        }
        // Get the total global score
        $globalScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $globalScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }
        return $this->render('OrgabatGameBundle:Pdf:allUsers.html.twig', array(
            'users' => $users,
            'categories' => $categoriesOfAllUsers,
            'stats' => [
                'global' => $globalScore
            ],
        ));
        /*$html2pdf = $this->get('html2pdf_factory')->create();
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        return new Response(
            $html2pdf->Output(__DIR__.$user->getName().".pdf", 'S'),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="' . $user->getName() . '".pdf"'
            )
        );
        */
    }

}
