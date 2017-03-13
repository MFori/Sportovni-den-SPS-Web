<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 17.12.2016
 * Time: 13:31
 */

namespace AppBundle\Controller\Referee;

use AppBundle\Entity\Rules;
use RestBundle\Model\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RulesController extends Controller
{
    /**
     * @Route("/pravidla", name="rules")
     * @return Response
     */
    public function summaryAction()
    {
        $rules = $this->getDoctrine()->getRepository('AppBundle:Rules')->findAll();

        return $this->render('referee/rules/rules.html.twig', array('rules' => $rules));
    }

    /**
     * @Route("/pravidla/{sport}", name="sport_rules")
     * @param $sport int
     * @param $request Request
     * @throws NotFoundException
     * @return Response
     */
    public function sportRulesAction($sport, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rules = $em->getRepository('AppBundle:Rules')->findOneBy(array(
            'sport_id' => $sport
        ));

        if (!$rules instanceof Rules) throw $this->createNotFoundException('Pravidla nenalezena');

        if ($request->isMethod('POST')) {
            $text = $request->request->get('rules_text');
            if ($text != null) {
                $rules->setText($text);
                $em->persist($rules);
                $em->flush();
                $this->addFlash('success', 'Pravidla úspěšně uložena.');
            }
        }

        return $this->render('referee/rules/detail.html.twig', array('rules' => $rules));
    }
}