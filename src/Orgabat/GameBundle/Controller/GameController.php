<?php

namespace Orgabat\GameBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class GameController extends Controller
{
    public function gameAction(Request $request, $id)
    {
        return $this->render('OrgabatGameBundle:Game:page_jeu.html.twig', [
            'id' => $id
        ]);
    }
}
