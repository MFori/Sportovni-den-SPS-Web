<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function dashboardAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $tournament \AppBundle\Entity\Tournament */
        $tournament = $em->getRepository('AppBundle:Tournament')->findOneBy(array(
            'active' => 1
        ));


        $teams_size = sizeof($em->getRepository('AppBundle:Team')->findBy(array('active' => true)));

        return $this->render('index.html.twig', array(
            'tournament' => $tournament,
            'teams_size' => $teams_size
        ));
    }
}
