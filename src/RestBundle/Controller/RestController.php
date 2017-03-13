<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 28.12.2016
 * Time: 14:30
 */

namespace RestBundle\Controller;

use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Doctrine\ORM\ORMException;
use RestBundle\Model\ErrorManager;
use RestBundle\Model\Exception\BadRequestException;
use RestBundle\Model\Exception\NotFoundException;
use RestBundle\Model\Exception\OutOfDateException;
use RestBundle\Model\Exception\UnauthorizedException;
use RestBundle\Model\RestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class RestController
 * @package RestBundle\Controller
 */
class RestController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Tournament
     */
    private $tournament;

    /**
     * Called if there is no action for rest route (starts with '/api/v1')
     * @throws NotFoundException
     */
    public function errorAction()
    {
        throw new NotFoundException;
    }

    /**
     * @param $method
     * @param $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        $this->request = $this->get('request_stack')->getCurrentRequest();

        $this->user = $this->loginUser($this->request);

        $params_count = sizeof($params);
        switch ($params_count) {
            case 0:
                return $this->$method();
            case 1:
                return $this->$method($params[0]);
            case 2:
                return $this->$method($params[0], $params[1]);
            case 3:
                return $this->$method($params[0], $params[1], $params[2]);
            case 4:
                return $this->$method($params[0], $params[1], $params[2], $params[3]);
            default:
                return call_user_func_array(array($this, $method), $params);
        }
    }

    /**
     * @param Request $request
     * @return User
     * @throws UnauthorizedException
     */
    private function loginUser(Request $request)
    {
        $header = explode('=', $request->headers->get('Authorization'));
        if (!isset($header[1])) throw new UnauthorizedException;
        $key = $header[1];

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(array(
            'apiKey' => $key
        ));

        if (!$user instanceof User) throw new UnauthorizedException;

        return $user;
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->user;
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        if ($this->request == null)
            $this->request = $this->get('request_stack')->getCurrentRequest();

        return $this->request;
    }

    /**
     * @param array $data
     * @param null $httpStatus
     * @param array $errors
     * @param bool $checkTournament
     * @return RestResponse
     * @throws OutOfDateException
     * @throws BadRequestException
     */
    protected function createResponse($data = array(), $httpStatus = null, $errors = array(), $checkTournament = true)
    {
        if ($checkTournament) {
            $activeTournament = $this->getActiveTournament();
            if (!$this->isTournamentActive($activeTournament, $this->getRequest()->headers->get('Tournament')))
                throw new OutOfDateException(array('tournament' => $activeTournament));
        }

        if ($httpStatus == null) {
            $httpStatus = $this->getStatusByRequest();
        }

        return new RestResponse($data, $httpStatus, $errors);
    }

    /**
     * @param $errorCode
     * @param null $message
     * @param array $todo
     * @return \RestBundle\Model\Error
     */
    protected function createError($errorCode, $message = null, $todo = array())
    {
        return ErrorManager::getError($errorCode, $message, $todo);
    }

    /**
     * @param $objectName
     * @param $criteria array
     * @return array
     * @throws BadRequestException
     */
    protected function getRepositoryWithCriteria($objectName, $criteria = array())
    {
        $criteria = array_merge($criteria, $this->getRequest()->query->all());
        $criteria['tournament'] = $this->getActiveTournament()->getId();
        $order = null;

        foreach ($criteria as $k => $v) {
            if ($v === true || $v === 'true') $criteria[$k] = 1;
            if ($v === false || $v === 'false') $criteria[$k] = 0;

            if ($k == 'order') {
                $order = $v;
                unset($criteria[$k]);
            }
        }

        if ($order != null) {
            $fields = explode(',', $order);
            $order = array();
            foreach ($fields as $field) {
                $o = substr($field, 0, 1);
                if ($o === '-') {
                    $f = substr($field, 1);
                    $order[$f] = 'DESC';
                } else {
                    $order[$field] = 'ASC';
                }
            }
        }

        try {
            $data = $this->getDoctrine()->getRepository($objectName)->findBy($criteria, $order);
            return $data;
        } catch (ORMException $e) {
            try {
                unset($criteria['tournament']);
                $data = $this->getDoctrine()->getRepository($objectName)->findBy($criteria, $order);
                return $data;
            } catch (ORMException $e2) {
                throw new BadRequestException;
            }
        }
    }

    /**
     * Get response code by request
     *
     * @return int
     */
    private function getStatusByRequest()
    {
        switch ($this->getRequest()->getMethod()) {
            case 'GET':
                return Response::HTTP_OK;
            case 'POST':
                return Response::HTTP_CREATED;
            case 'PUT':
                return Response::HTTP_OK;
            case 'DELETE':
                return Response::HTTP_NO_CONTENT;
            default:
                return Response::HTTP_OK;
        }
    }

    /**
     * @param int $id
     * @param Tournament $tournament
     * @return bool
     * @throws BadRequestException
     */
    private function isTournamentActive($tournament, $id)
    {
        if ($id == null)
            throw new BadRequestException();

        return $tournament->getId() == $id;
    }

    /**
     * @return Tournament
     */
    protected function getActiveTournament()
    {
        if ($this->tournament == null) {
            $this->tournament = $this->getDoctrine()->getRepository('AppBundle:Tournament')->findOneBy(
                array('active' => true));
        }

        return $this->tournament;
    }

}