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

    /**
     * @Route("/regles",)
     */
    public function showRules(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_regles.html.twig');
    }

    /**
     * @Route("/identification",)
     */
    public function showConnectionFormUser(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_co.html.twig');
    }

    /**
     * @Route("/menu",)
     */
    public function showMenu(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:User:page_rubriques.html.twig');
    }


        /**
     * @Route("/adminBoard",)
     */
    public function showAdmin(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('OrgabatGameBundle:Admin:page_dashboard.html.twig');
    }
}
