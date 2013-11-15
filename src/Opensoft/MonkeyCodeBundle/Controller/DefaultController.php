<?php

namespace Opensoft\MonkeyCodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Declares Symfony Security routes.
     *
     * @Route("/logout", name="logout")
     * @Route("/login_check", name="login_check")
     */
    public function fakeAction()
    {

    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'OpensoftMonkeyCodeBundle:Default:login.html.twig',
            array('last_username' => $session->get(SecurityContext::LAST_USERNAME), 'error' => $error,)
        );
    }
}
