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
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    /**
     * @Route("/regles",)
     */
    public function showRules(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('user/page_regles.html.twig');
    }

    /**
     * @Route("/identification",)
     */
    public function showConnectionFormUser(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('user/page_co.html.twig');
    }

    /**
     * @Route("/menu",)
     */
    public function showMenu(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('user/page_rubriques.html.twig');
    }


        /**
     * @Route("/adminBoard",)
     */
    public function showAdmin(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('admin/page_dashboard.html.twig');
    }
}
