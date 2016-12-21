<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $exercises = $em
            ->getRepository('OrgabatGameBundle:Exercise')
            ->getExercisesOfCategory($category)
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

        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_jeux.html.twig', [
            'category' => $category,
            'exercises' => $exercises,
            'dones' => $exDones
        ]);
    }

    public function showAdminAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $sections = $em
            ->getRepository('OrgabatGameBundle:Section')
            ->findBy(array(), array('id' => 'asc'))
        ;
        $fullList = [];
        foreach ($sections as $section) {
            $listApprentices = $em
                ->getRepository('OrgabatGameBundle:Apprentice')
                ->findBy(array('section' => $section))
            ;
            $fullList[$section->getName()] =  $listApprentices;
        }


        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:Admin:page_dashboard.html.twig', ['lists' => $fullList]);
    }
}
