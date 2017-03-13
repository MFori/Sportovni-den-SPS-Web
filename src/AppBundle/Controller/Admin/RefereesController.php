<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 17.12.2016
 * Time: 13:31
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\Referee;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\UserType;

class RefereesController extends Controller
{
    /**
     * @Route("/rozhodci", name="referees")
     * @return Response
     */
    public function summaryAction()
    {
        $referees = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        return $this->render('admin/referees/referees.html.twig', array('referees' => $referees));
    }

    /**
     * @Route("/rozhodci/novy", name="referees_new")
     * @Route("/rozhodci/upravit/{id}", name="referees_edit")
     * @param $id
     * @param $request Request
     * @return Response
     */
    public function newEditAction($id = null, Request $request)
    {
        $user = null;

        if ($id != null) {
            $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
            $referee = new Referee($user);
        } else {
            $referee = new Referee();
        }

        $form = $this->createForm(UserType::class, $referee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $referee Referee */
            $referee = $form->getData();

            $msg = $this->existUser($referee->getEmail(), $referee->getUsername(), $user ? $user->getId() : null);
            if (is_string($msg)) {
                $this->addFlash('danger', $msg);
            } else {
                if ($id == null)
                    return $this->saveUser($referee);
                else
                    return $this->saveUser($referee, $user);
            }
        }

        return $this->render('admin/referees/new.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    /**
     * Save new user/update current
     *
     * @param Referee $referee
     * @param User|null $user
     * @return Response
     */
    private function saveUser(Referee $referee, User $user = null)
    {
        echo $referee->getPlainPassword();

        if ($user == null) {
            $user = new User();
            $encoder = $this->get('security.password_encoder');

            if ($referee->getGenerate()) $referee->setPlainPassword(Referee::randomPassword());

            $user->setPassword($encoder->encodePassword($user, $referee->getPlainPassword()));
            $user->setApiKey(md5(uniqid($referee->getUsername(), true)));

            $this->addFlash('success', 'Rozhodčí úspěšně vytvořen.');
        } else {
            $this->addFlash('success', 'Rozhodčí úspěšně upraven.');
        }

        $user->setEmail($referee->getEmail());
        $user->setUsername($referee->getUsername());
        $user->setRoles($referee->getAdmin() ? 'ROLE_USER;ROLE_ADMIN' : 'ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('referees_edit', array('id' => $user->getId()));
    }

    /**
     * Check if exist user with email/username
     *
     * @param $email
     * @param $username
     * @param $user_id
     * @return bool|string
     */
    private function existUser($email, $username, $user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneBy(array('email' => $email));
        if ($user instanceof User && $user->getId() != $user_id) return 'Uživatel s tímto emailem již existuje.';

        $user = $em->getRepository('AppBundle:User')->findOneBy(array('username' => $username));
        if ($user instanceof User && $user->getId() != $user_id) return 'Uživatel s tímto jménem již existuje.';

        return false;
    }

    /**
     * @Route("/rozhodci/detail/{id}", name="referees_detail")
     * @param $id
     * @return Response
     */
    public function detailAction($id)
    {
        $referee = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (!$referee instanceof User) throw new NotFoundHttpException;

        return $this->render('admin/referees/detail.html.twig', array('referee' => $referee));
    }

    /**
     * @Route("/rozhodci/odstranit/{id}", name="referees_delete")
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $referee = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (!$referee instanceof User) throw new NotFoundHttpException;

        $em = $this->getDoctrine()->getManager();
        $em->remove($referee);
        $em->flush();

        $this->addFlash('success', 'Rozhodčí úspěšně odstraněn.');

        return $this->redirectToRoute('referees');
    }
}