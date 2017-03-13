<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 17.12.2016
 * Time: 13:31
 */

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NotificationsController extends Controller
{
    /**
     * @Route("/notifikace", name="notifications")
     * @return Response
     */
    public function summaryAction()
    {
        $notifications = $this->getDoctrine()->getRepository('AppBundle:Notification')->findBy(array(), array(
            'date' => 'DESC'
        ));

        return $this->render('admin/notifications/all.html.twig', array('notifications' => $notifications));
    }
}