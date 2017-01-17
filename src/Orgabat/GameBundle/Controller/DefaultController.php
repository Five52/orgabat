<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Orgabat\GameBundle\Form\TrainerUpdateType;
use Orgabat\GameBundle\Form\AdminUpdateType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Orgabat\GameBundle\Entity\Category;

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
        $tries = $em->getRepository('OrgabatGameBundle:HistoryRealisation')->findBy(['user' => $user]);

        // Get the best try of each exercises
        $bestTries = [];
        foreach ($tries as $try) {
            $exerciseId = $try->getExercise()->getId();
            $currentTryScore = $try->getScore();

            if (!isset($bestTries[$exerciseId])) {
                $bestTries[$exerciseId] = $try;
            } else {
                $storedTryScore = $bestTries[$exerciseId]->getScore();
                if ($storedTryScore < $currentTryScore) {
                    $bestTries[$exerciseId] = $try;
                }
            }
        }

        $finishedCount = 0;
        foreach ($bestTries as $exerciseId => $try) {
            if ($try->getScore() >= $try->getExercise()->getMinScore()) {
                $finishedCount++;
            }
        }

        // Get the total global score
        $globalCategScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $globalCategScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalCategScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalCategScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }

        // Get the total user score
        $userCategScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($bestTries as $try) {
            $userCategScore['healthNote'] += $try->getHealthNote();
            $userCategScore['organizationNote'] += $try->getOrganizationNote();
            $userCategScore['businessNotorietyNote'] += $try->getBusinessNotorietyNote();
        }

        return $this->render('OrgabatGameBundle:User:page_rubriques.html.twig', [
            'categories' => $categories,
            'stats' => [
                'user' => $userCategScore,
                'global' => $globalCategScore
            ],
            'minigames' => [
                'finished' => $finishedCount,
                'total' => count($exercises)
            ]
        ]);
    }

    /**
     * @ParamConverter("category", options={"mapping": {"category_id": "id"}})
     */
    public function showGamesAction(Category $category, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        var_dump($user->getId());
        $exercises = $em
            ->getRepository('OrgabatGameBundle:Exercise')
            ->getExercisesOfCategoryWithUserInfos($category, $user)
        ;
        $tries = $em
            ->getRepository('OrgabatGameBundle:ExerciseHistory')
            ->findBy([
                'exercise' => $exercises,
                'user' => $user
            ]);

        // Get the best try of each exercises
        $bestTries = [];
        $tryCount = [];
        foreach ($tries as $try) {
            $exerciseId = $try->getExercise()->getId();
            $currentTryScore = $try->getScore();

            if (!isset($bestTries[$exerciseId])) {
                $bestTries[$exerciseId] = $try;
                $tryCount[$exerciseId] = 1;
            } else {
                $storedTryScore = $bestTries[$exerciseId]->getScore();
                if ($storedTryScore < $currentTryScore) {
                    $bestTries[$exerciseId] = $try;
                    ++$tryCount[$exerciseId];
                }
            }
        }

        // STATS
        // Get the total user score
        $userCategScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($bestTries as $try) {
            $userCategScore['healthNote'] += $try->getHealthNote();
            $userCategScore['organizationNote'] += $try->getOrganizationNote();
            $userCategScore['businessNotorietyNote'] += $try->getBusinessNotorietyNote();
        }
        // Get the total global score
        $globalCategScore = ['healthNote' => 0, 'organizationNote' => 0, 'businessNotorietyNote' => 0];
        foreach ($exercises as $exercise) {
            $globalCategScore['healthNote'] += $exercise->getHealthMaxNote();
            $globalCategScore['organizationNote'] += $exercise->getOrganizationMaxNote();
            $globalCategScore['businessNotorietyNote'] += $exercise->getBusinessNotorietyMaxNote();
        }

        return $this->render('OrgabatGameBundle:User:page_jeux.html.twig', [
            'category' => $category,
            'exercises' => $exercises,
            'bestTries' => $bestTries,
            'categStats' => [
                'user' => $userCategScore,
                'global' => $globalCategScore
            ],
            'tryCount' => $tryCount
        ]);
    }

    // Dashboard principal de l'administrateur
    public function showAdminAction()
    {

        $em = $this->getDoctrine()->getManager();
        $sections = $em
            ->getRepository('OrgabatGameBundle:Section')
            //->findBy([], ['id' => 'asc'])
            ->getWithTrainers()
        ;
        $fullList = [];
        foreach ($sections as $section) {
            // Pour chaque classe

            // On crée une liste d'apprentis
            $listApprentices = $em
                ->getRepository('OrgabatGameBundle:Apprentice')
                ->findBy(['section' => $section])
            ;

            // On crée une liste d'enseignants
            $listTrainers = $section->getTrainers();
                //$em
                //->getRepository('OrgabatGameBundle:Trainer')
                //->findBySections($section)
            ;

            // On associe la classe, les enseignants et les apprentis
            $listApprentices = [];
            foreach ($listTrainers as $trainer) {
                $listApprentices[] = $trainer;
            }
            $fullList[$section->getName()] = $listApprentices;
        }
        $apprenticesNoSection = $em
            ->getRepository('OrgabatGameBundle:Apprentice')
            ->findBy(['section' => null])
        ;

        return $this->render('OrgabatGameBundle:Admin:page_dashboard.html.twig', [
            'lists' => $fullList,
            'listNoSection' => $apprenticesNoSection
        ]);
    }

    // Liste des classes
    public function showSectionsAction()
    {
        // On récupère les informations de l'utilisateur du site
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $fullList = [];
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // S'il s'agit d'un admin on retourne tous les classes avec tous les apprentis
            $sections = $em
                ->getRepository('OrgabatGameBundle:Section')
                ->findBy(array(), array('id' => 'asc'))
            ;
            foreach ($sections as $section) {
                $listApprentices = $em
                    ->getRepository('OrgabatGameBundle:Apprentice')
                    ->findBy(array('section' => $section))
                ;
                $fullList[$section->getName()] =  $listApprentices;
            }
        }else {
            // S'il s'agit d'un enseignant, on retoune la classe qu'il anime (TODO)
            $section = $user->getSection();
            $listApprentices = $em
                ->getRepository('OrgabatGameBundle:Apprentice')
                ->findBy(array('section' => $section))
            ;
            $fullList[$section->getName()] =  $listApprentices;
        }

        return $this->render('OrgabatGameBundle:Admin:showSections.html.twig', [
            "lists" => $fullList
        ]);

    }

    public function showEditInfosAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(AdminUpdateType::class, $user);
        } else {
            $form = $this->createForm(TrainerUpdateType::class, $user);
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
            'form' => $form->createView()
        ]);
    }
}
