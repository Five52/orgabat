<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:Default:index.html.twig');
    }

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

    public function showGamesAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $exercises = $em
            ->getRepository('OrgabatGameBundle:Exercise')
            ->findBy([
                'category' => $id,
            ], [
                'category' => 'ASC',
            ])
        ;

        $dones = $em
            ->getRepository('OrgabatGameBundle:HistoryRealisation')
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

        $category = $em
            ->getRepository('OrgabatGameBundle:Category')
            ->findById($id)
        ;

        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_jeux.html.twig', [
            'category' => $category[0],
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
