<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Orgabat\GameBundle\Form\TrainerSelfUpdateType;
use Orgabat\GameBundle\Form\AdminUpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Orgabat\GameBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Security("has_role('ROLE_APPRENTICE')")
     */
    public function showRulesAction(Request $request)
    {
        return $this->render('OrgabatGameBundle:User:page_regles.html.twig');
    }

    public function showConnectionFormUserAction(Request $request)
    {
        return $this->render('OrgabatGameBundle:User:page_co.html.twig');
    }


    /**
     * @Security("has_role('ROLE_APPRENTICE')")
     */
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
     * @Security("has_role('ROLE_APPRENTICE')")
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
            'categStats' => [
                'user' => $userCategScore,
                'global' => $globalCategScore,
            ],
            'tryCount' => [],
        ]);
    }

    /**
     * Dashboard principal de l'administrateur
     * @Security("has_role('ROLE_TRAINER')")
     */
    public function showAdminAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->forward('OrgabatGameBundle:Default:showSections');
        }

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

    /**
     * @Security("has_role('ROLE_TRAINER')")
     */
    public function showSectionsAction()
    {
        // On récupère les informations de l'utilisateur du site
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // S'il s'agit d'un admin on retourne tous les classes avec tous les apprentis
            $sections = $em
                ->getRepository('OrgabatGameBundle:Section')
                ->getWithTrainersAndApprentices()
            ;
        } else {
            // S'il s'agit d'un formateur, on retoune la classe qu'il anime
            $sections = $em
                ->getRepository('OrgabatGameBundle:Trainer')
                ->getWithSections($user->getId())
                ->getSections()
            ;
        }

        return $this->render('OrgabatGameBundle:Admin:showSections.html.twig', [
            "sections" => $sections
        ]);

    }

    /**
     * @Security("has_role('ROLE_TRAINER')")
     */
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

            $this->addFlash(
                'message',
                'Vos informations ont bien été modifiées !'
            );

            return $this->redirectToRoute('default_editinfos');
        }

        return $this->render('OrgabatGameBundle:Admin:showEditInfos.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
