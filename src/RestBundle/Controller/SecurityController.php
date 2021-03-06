<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 28.12.2016
 * Time: 16:56
 */

namespace RestBundle\Controller;

use AppBundle\Entity\User;
use RestBundle\Model\Exception\BadRequestException;
use RestBundle\Model\RestResponse;
use RestBundle\Model\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityController
 * @package RestBundle\Controller
 */
class SecurityController extends RestController
{
    /**
     * Login Action
     *
     * @Route("/login", name="api_login")
     * @Method("POST")
     * @param $request Request
     * @throws BadRequestException
     * @return RestResponse
     */
    public function loginAction(Request $request)
    {
        $username = $request->request->get('username');
        $pass = $request->request->get('pass');
        $iv = $request->request->get('iv');

        if ($username == null || $pass == null || $iv == null || strlen($iv) != 16)
            throw new BadRequestException;

        $pass = Security::decrypt($pass, $iv);

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(array(
            'username' => $username
        ));

        if ($user instanceof User) {

            $encoder = $this->get('security.password_encoder');
            $pass = $encoder->encodePassword($user, $pass);

            if ($pass != $user->getPassword()) throw new BadRequestException;

            return $this->createResponse(array('user' => $user), Response::HTTP_OK, array(), false);

        } else {
            throw new BadRequestException;
        }
    }

    /**
     * Route of Rest for getting current active tournament
     * @Route("/tournaments/active")
     * @Method("GET")
     * @return RestResponse
     */
    public function getCurrentTournament()
    {
        $tournament = $this->getDoctrine()->getRepository('AppBundle:Tournament')->findOneBy(
            array('active' => true)
        );

        return $this->createResponse(array('tournament' => $tournament), null, array(), false);
    }
}