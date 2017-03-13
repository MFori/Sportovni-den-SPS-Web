<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 18.12.2016
 * Time: 18:58
 */

namespace AppBundle\Form;

use AppBundle\Entity\User;

class Referee extends User
{
    private $generate = true;
    private $send = true;
    private $admin = false;

    private $new;

    public function __construct(User $user = null) {
        if($user != null) {
            $this->setEmail($user->getEmail());
            $this->setUsername($user->getUsername());
            $this->setAdmin($user->isAdmin());
        }

        $this->new = $user == null;
    }

    /**
     * @return mixed
     */
    public function getGenerate()
    {
        return $this->generate;
    }

    /**
     * @param mixed $generate
     */
    public function setGenerate($generate)
    {
        $this->generate = $generate;
    }

    /**
     * @return mixed
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @param mixed $send
     */
    public function setSend($send)
    {
        $this->send = $send;
    }

    /**
     * @return mixed
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return mixed
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * Generate random password
     * @return string
     */
    public static function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}