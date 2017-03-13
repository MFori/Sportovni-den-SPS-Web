<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 19.12.2016
 * Time: 19:06
 */

namespace AppBundle\Form;

use AppBundle\Entity\Scoring;

include_once(__DIR__ . '/../Entity/ScoringType.php');

define('SPORT_FOTBAL', 1);
define('SPORT_NOHEJBAL', 2);
define('SPORT_BASKETBAL', 3);
define('SPORT_VOLEJBAL', 4);
define('SPORT_RINGO', 5);
define('SPORT_PRETAH_LANEM', 6);
define('SPORT_PING_PONG', 7);
define('SPORT_SHYBY', 8);
define('SPORT_SIPKY', 9);
define('SPORT_TROJSKOK', 10);

class Sport implements \ArrayAccess
{
    private $id;
    private $title;
    private $active;

    private $scoring_type = TYPE_INDIVIDUALS;
    private $win = null;
    private $isDraw = false;
    private $draw = null;
    private $lose = null;
    private $forfeit = null;
    private $sets = null;
    private $set_points = null;
    private $time = null;

    public function __construct(\AppBundle\Entity\Sport $sport = null)
    {
        if($sport != null) {
            $this->id = $sport->getId();
            $this->title = $sport->getName();
            $this->active = $sport->getActive();

            $this->initAttributes($sport);
        }
    }

    /**
     * @return \AppBundle\Entity\Scoring
     */
    public function createScoring()
    {
        $scoring = new Scoring();

        $scoring->setSportId($this->id);
        $scoring->setWin($this->win);
        $scoring->setLose($this->lose);
        $scoring->setDraw($this->draw);
        $scoring->setTypeId($this->scoring_type);
        $scoring->setForfeit($this->forfeit);
        $scoring->setSets($this->sets);
        $scoring->setSetPoints($this->set_points);
        $scoring->setTime($this->time);

        return $scoring;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getScoringType()
    {
        return $this->scoring_type;
    }

    /**
     * @param mixed $scoring_type
     */
    public function setScoringType($scoring_type)
    {
        $this->scoring_type = $scoring_type;
    }

    /**
     * @return mixed
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * @param mixed $win
     */
    public function setWin($win)
    {
        $this->win = $win;
    }

    /**
     * @return mixed
     */
    public function getIsDraw()
    {
        return $this->isDraw;
    }

    /**
     * @param mixed $isDraw
     */
    public function setIsDraw($isDraw)
    {
        $this->isDraw = $isDraw;
    }

    /**
     * @return mixed
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * @param mixed $draw
     */
    public function setDraw($draw)
    {
        $this->draw = $draw;
    }

    /**
     * @return mixed
     */
    public function getLose()
    {
        return $this->lose;
    }

    /**
     * @param mixed $lose
     */
    public function setLose($lose)
    {
        $this->lose = $lose;
    }

    /**
     * @return mixed
     */
    public function getForfeit()
    {
        return $this->forfeit;
    }

    /**
     * @param mixed $forfeit
     */
    public function setForfeit($forfeit)
    {
        $this->forfeit = $forfeit;
    }

    /**
     * @return mixed
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @param mixed $sets
     */
    public function setSets($sets)
    {
        $this->sets = $sets;
    }

    /**
     * @return mixed
     */
    public function getSetPoints()
    {
        return $this->set_points;
    }

    /**
     * @param mixed $set_points
     */
    public function setSetPoints($set_points)
    {
        $this->set_points = $set_points;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }

    private function initAttributes(\AppBundle\Entity\Sport $sport) {

        switch($sport->getId()) {
            case SPORT_FOTBAL:
                $this->scoring_type = TYPE_GROUP_FINALE;
                $this->win = 3;
                $this->isDraw = true;
                $this->draw = 1;
                $this->lose = 0;
                $this->forfeit = 3;
                $this->time = "10";
                break;
            case SPORT_NOHEJBAL:
                $this->scoring_type = TYPE_GROUP_FINALE;
                $this->sets = 2;
                $this->set_points = 11;
                break;
            case SPORT_BASKETBAL:
                $this->scoring_type = TYPE_GROUP_FINALE;
                $this->win = 3;
                $this->isDraw = true;
                $this->draw = 1;
                $this->lose = 0;
                $this->forfeit = 3;
                $this->time = "10";
                break;
            case SPORT_VOLEJBAL:
                $this->scoring_type = TYPE_GROUP_FINALE;
                $this->sets = 2;
                $this->set_points = 25;
                break;
            case SPORT_RINGO:
                $this->scoring_type = TYPE_GROUP_FINALE;
                break;
            case SPORT_PRETAH_LANEM:
                $this->scoring_type = TYPE_GROUP_FINALE;
                break;
            case SPORT_PING_PONG:
                $this->scoring_type = TYPE_GROUP_FINALE;
                $this->sets = 2;
                $this->set_points = 11;
                break;
            case SPORT_SHYBY:
                $this->scoring_type = TYPE_INDIVIDUALS;
                break;
            case SPORT_SIPKY:
                $this->scoring_type = TYPE_INDIVIDUALS;
                break;
            case SPORT_TROJSKOK:
                $this->scoring_type = TYPE_INDIVIDUALS;
                break;
        }

        $this->win = 3;
    }
}