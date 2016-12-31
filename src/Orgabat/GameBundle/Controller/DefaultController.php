<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Orgabat\GameBundle\Entity\Category;

class DefaultController extends Controller
{
    public function showConnectionFormUserAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_co.html.twig');
    }

    public function showRulesAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_regles.html.twig');
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
     * Find games with best results (maximal summed score for minimal time) for all games by user
     * @ParamConverter("category", options={"mapping": {"category_id": "id"}})
     */
    public function showGamesAction(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $exercises = $em->getRepository('OrgabatGameBundle:Exercise')->getExercisesOfCategory($category);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $dones = $em
            ->getRepository('OrgabatGameBundle:HistoryRealisation')
            ->findBy(['exercise' => $exercises, 'user' => $user,], [ 'timer' => 'DESC' ]);
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

    public function showAdminAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:Admin:page_dashboard.html.twig');
    }
}
