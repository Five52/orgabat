<?php

namespace Orgabat\GameBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class GameController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function gameAction(Request $request, $id)
    {
        return $this->render('user/page_game.html.twig', [
            'id' => $id
        ]);
    }
}
