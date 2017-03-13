<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 30.12.2016
 * Time: 12:06
 */

namespace RestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class TeamsController
 * @package RestBundle\Controller
 */
class TeamsController extends RestController
{
    /**
     * @Route("/teams")
     * @Method("GET")
     * @return \RestBundle\Model\RestResponse
     * @throws \RestBundle\Model\Exception\BadRequestException
     */
    public function teamsAction()
    {
        $teams = $this->getRepositoryWithCriteria('AppBundle:Team');

        return $this->createResponse(array('teams' => $teams));
    }
}