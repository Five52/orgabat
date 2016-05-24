<?php

namespace Orgabat\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrgabatGameBundle:Default:index.html.twig');
    }
}
