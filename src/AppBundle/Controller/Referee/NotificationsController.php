<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 17.12.2016
 * Time: 13:31
 */

namespace AppBundle\Controller\Referee;

use AppBundle\Entity\Addressee;
use AppBundle\Entity\Notification;
use AppBundle\Model\NotificationsManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationsController extends Controller
{
    /**
     * @Route("/notifikace/nova", name="notifications_new")
     * @param $request Request
     * @return Response
     */
    public function newAction(Request $request)
    {
        if($request->isMethod('POST')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();

            $addressee = $em->getRepository('AppBundle:Addressee')->find(Addressee::EVERYBODY);

            $notification = new Notification();
            $notification->setTitle($request->request->get('title'))
                ->setText($request->request->get('text'))
                ->setAddressee($addressee)
                ->setDate(new \DateTime())
                ->setSender($user);

            $em->persist($notification);
            $em->flush();

            if(NotificationsManager::send($notification)){
                $this->addFlash('success', 'Notifikace odeslána.');
            } else {
                $this->addFlash('danger', 'Notifikaci se nepodařilo odeslat.');
            }
        }

        return $this->render('referee/notifications/new.html.twig');
    }

    /**
     * @Route("/notifikace/odeslane", name="notifications_sent")
     * @return Response
     */
    public function sentAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $notifications = $this->getDoctrine()->getRepository('AppBundle:Notification')->findBy(array(
            'sender' => $user
        ), array(
            'date' => 'DESC'
        ));

        return $this->render('referee/notifications/sent.html.twig', array(
            'notifications' => $notifications
        ));
    }

    /**
     * @Route("/notifikace/prijate", name="notifications_received")
     * @return Response
     */
    public function receivedAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        /* @var $notifications Notification[] */
        $notifications = $this->getDoctrine()->getRepository('AppBundle:Notification')->findBy(
            array(),array('date' => 'DESC')
        );

        $res = array();
        foreach ($notifications as $notification) {
            if (($notification->getAddresseeId() == Addressee::REFEREES ||
                    $notification->getAddresseeId() == Addressee::EVERYBODY) &&
                $notification->getSender() != $user
            )
                $res[] = $notification;
        }

        return $this->render('referee/notifications/received.html.twig', array(
            'notifications' => $res
        ));
    }

    /**
     * @Route("/notifikace/detail/{id}", name="notifications_detail")
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $notification = $this->getDoctrine()->getRepository('AppBundle:Notification')->find($id);

        return $this->render('referee/notifications/detail.html.twig', array(
            'notification' => $notification
        ));
    }
}