<?php
/**
 * Created by PhpStorm.
 * User: lcoue
 * Date: 27/05/2016
 * Time: 11:42
 */

namespace Orgabat\GameBundle\Handler;


use Orgabat\GameBundle\Entity\Admin;
use Orgabat\GameBundle\Entity\Teacher;
use Orgabat\GameBundle\Entity\Trainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;


class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }


    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();
        if ($user instanceof Admin) {
            return new RedirectResponse($this->router->generate('default_admin_board'));
        }else if ( $user instanceof Trainer) {
            return new RedirectResponse($this->router->generate('default_sections'));
        }
        else {
            return new RedirectResponse($this->router->generate('default_rules'));
        }
    }
}