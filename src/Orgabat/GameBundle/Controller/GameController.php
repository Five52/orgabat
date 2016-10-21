<?php
/**
 * Created by PhpStorm.
 * User: Logan
 * Date: 24/05/2016
 * Time: 12:49
 */

namespace Orgabat\GameBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{
    public function gameAction(Request $request, $id)
    {
        return $this->render('OrgabatGameBundle:Game:page_jeu.html.twig', [
            'id' => $id,
        ]);
    }
}
