<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 30.12.2016
 * Time: 12:06
 */

namespace RestBundle\Controller;

use AppBundle\Entity\Scoring;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class SportsController
 * @package RestBundle\Controller
 */
class SportsController extends RestController
{
    /**
     * Route of Rest for getting sports
     * @Route("/sports")
     * @Method("GET")
     * @return \RestBundle\Model\RestResponse
     * @throws \RestBundle\Model\Exception\BadRequestException
     */
    public function sportsAction()
    {
        /* @var \AppBundle\Entity\Sport[] $sports */
        $sports = $this->getRepositoryWithCriteria('AppBundle:Sport');
        $res = array();
        $em = $this->getDoctrine()->getManager();

        foreach ($sports as $sport) {
            $arr = $sport->restSerialize();
            $scoring = $em->getRepository('AppBundle:Scoring')->findOneBy(array(
                'sport' => $sport,
                'tournament' => $this->getActiveTournament()
            ));
            if ($scoring instanceof Scoring) {
                $arr['scoring'] = $scoring->getType()->getId();
                $arr['sets'] = $scoring->getSets();
                $arr['sets_points'] = $scoring->getSetPoints();
                $arr['time'] = $scoring->getTime();
            } else {
                $arr['scoring'] = null;
                $arr['sets'] = null;
                $arr['sets_points'] = null;
                $arr['time'] = null;
            }
            $res[] = $arr;
        }

        return $this->createResponse(array('sports' => $res));
    }
}