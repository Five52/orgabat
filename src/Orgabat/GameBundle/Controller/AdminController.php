<?php

namespace Orgabat\GameBundle\Controller;

use Orgabat\GameBundle\Entity\User;
use Orgabat\GameBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function createUserAction(Request $request)
    {
        $user = new User(); //TODO: Phpstorm error: Can't instanciate Abstract Class
        $form = $this->createForm(UserType::class, $user);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->getSession()->getFlashBag()->add('notice', 'Utilisateur enregistré !');
            return $this->redirectToRoute('');
        }

        return $this->render('', [
            'form' => $form->createView()
        ]);
    }
}
