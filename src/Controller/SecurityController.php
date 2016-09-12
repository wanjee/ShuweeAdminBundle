<?php

namespace Wanjee\Shuwee\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * Class SecurityController
 * @package Wanjee\Shuwee\AdminBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="shuwee_login")
     * @Method("GET")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'ShuweeAdminBundle:Security:login.html.twig',
            [
                // last username entered by the user
                'last_username' => $session->get(Security::LAST_USERNAME),
                'error'         => $error,
            ]
        );
    }

    /**
     * @Route("/login_check", name="shuwee_login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="shuwee_logout")
     */
    public function logoutAction()
    {

    }

}

