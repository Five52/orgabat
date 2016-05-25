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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

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
        $html = $templating->render('OrgabatGameBundle:User:page_co.html.twig', [
            'form'=>$form->createView()
        ]);
        return new Response($html);
    }

    /**
     * @Method({"GET"})
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        $params = array(
            "last_username" => $session->get(Security::LAST_USERNAME),
            "error"         => $error,
        );

        return $params;
    }


    /**
     * @Method({"POST"})
     * @Route("/login_check", name="login_check")
     */
    public function check()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Method({"GET"})
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}