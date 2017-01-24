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
        $categories = $em
            ->getRepository('OrgabatGameBundle:Category')
            ->findAll()
        ;

        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_rubriques.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @ParamConverter("category", options={"mapping": {"category_id": "id"}})
     */
    public function showGamesAction(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        var_dump($user->getId());
        $exercises = $em
            ->getRepository('OrgabatGameBundle:Exercise')
            ->getExercisesOfCategoryWithUserInfos($category, $user)
        ;

        $dones = $em
            ->getRepository('OrgabatGameBundle:ExerciseHistory')
            ->findBy([
                'exercise' => $exercises,
                // 'user_id' => $user_id,
            ])
        ;
        $exDones = [];
        foreach ($dones as $done) {
            foreach ($exercises as $exercise) {
                if ($done->getExercise()->getId() === $exercise->getId()) {
                    $exDones[$exercise->getId()] = [
                        'healthNote' => $done->getHealthNote(),
                        'organizationNote' => $done->getOrganizationNote(),
                        'businessNotorietyNote' => $done->getBusinessNotorietyNote(),
                    ];
                }
            }
        }

        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_jeux.html.twig', [
            'category' => $category,
            'exercises' => $exercises,
            'dones' => $exDones
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
        $apprenticesNoSection = $em
            ->getRepository('OrgabatGameBundle:Apprentice')
            ->findBy(['section' => null])
        ;

        return $this->render('OrgabatGameBundle:Admin:page_dashboard.html.twig', [
            'sections' => $sections,
            'listNoSection' => $apprenticesNoSection
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
