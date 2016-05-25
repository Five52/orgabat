<?php
/**
 * Created by PhpStorm.
 * User: Logan
 * Date: 24/05/2016
 * Time: 12:49
 */

namespace GameBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{
    /**
     * @Route("game/{id}", name="game")
     */
    public function gameAction(Request $request, $id)
    {
        // replace this example code with whatever you need
        return $this->render('user/partial_game.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'id' => $id
        ]);
    }
}
