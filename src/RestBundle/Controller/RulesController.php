<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 30.12.2016
 * Time: 12:07
 */

namespace RestBundle\Controller;

use AppBundle\Entity\Rules;
use RestBundle\Model\Exception\NotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class RulesController
 * @package RestBundle\Controller
 */
class RulesController extends RestController
{
    /**
     * Route of Rest for getting rules for sport(s)
     * @Route("/rules/{sport}", defaults={"sport": null})
     * @Method("GET")
     * @param null|int $sport
     * @return \RestBundle\Model\RestResponse
     * @throws NotFoundException
     * @throws \RestBundle\Model\Exception\BadRequestException
     */
    public function rulesAction($sport = null)
    {
        if($sport != null) {
            $rules = $this->getDoctrine()->getRepository('AppBundle:Rules')->find($sport);
            if(!$rules instanceof Rules) throw new NotFoundException;
            return $this->createResponse(array('rules' => $rules));
        } else {
            $rules = $this->getRepositoryWithCriteria('AppBundle:Rules');
            return $this->createResponse(array('rules' => $rules));
        }
    }
}