<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller
{
    /**
     * @Route("/prihlaseni", name="login")
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        /*$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find(2);

        $encoder = $this->get('security.password_encoder');
        $user->setPassword($encoder->encodePassword($user, 'admin123'));

        $em->persist($user);
        $em->flush();*/

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/odhlaseni", name="logout")
     */
    public function logoutAction()
    {
        // EMPTY
    }

    /**
     * @Route("/test")
     * @return Response
     */
    public function test(Request $request)
    {
        $t = $this->get('security.token_storage')->getToken()->getUser();
        if ($t != null) {
            echo $t;
        } else {
            echo 'yes';
        }

        $key = explode('=', $request->headers->get('Authorization'))[1];

        if ($key == 'AIzaSyDBnzqPaZ') {
            echo 'ttt';
            $token = new UsernamePasswordToken('user', 'password', 'public', array('ROLE_ADMIN'));
        }
        //$this->get('security.token_storage')->setToken($token);

        return new Response('');
    }
}
