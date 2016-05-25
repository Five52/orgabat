<?php
/**
 * Created by PhpStorm.
 * User: lcoue
 * Date: 24/05/2016
 * Time: 17:32
 */

namespace Orgabat\GameBundle\Controller;


use Orgabat\GameBundle\Entity\User;
use Orgabat\GameBundle\Form\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * @Route("/connexion")
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserForm::class,$user);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
        }



        $templating = $this->container->get('templating');
        $html = $templating->render('user/page_co.html.twig',array(
            'form'=>$form->createView()
        ));
        return new Response($html);
    }
}