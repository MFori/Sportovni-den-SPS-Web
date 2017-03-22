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

/**
 * Sport type Football
 * @var string
 */
define('SPORT_FOTBAL', 1);
/**
 * Sport type Football (nohejbal)
 * @var string
 */
define('SPORT_NOHEJBAL', 2);
/**
 * Sport type Basketball
 * @var string
 */
define('SPORT_BASKETBAL', 3);
/**
 * Sport type Volleyball
 * @var string
 */
define('SPORT_VOLEJBAL', 4);
/**
 * Sport type Ringo
 * @var string
 */
define('SPORT_RINGO', 5);
/**
 * Sport type Rope
 * @var string
 */
define('SPORT_PRETAH_LANEM', 6);
/**
 * Sport type Ping pong
 * @var string
 */
define('SPORT_PING_PONG', 7);
/**
 * Sport type Push-ups
 * @var string
 */
define('SPORT_SHYBY', 8);
/**
 * Sport type Darts
 * @var string
 */
define('SPORT_SIPKY', 9);
/**
 * Sport type Jumping
 * @var string
 */
define('SPORT_TROJSKOK', 10);

/**
 * Class Sport
 * @package AppBundle\Form
 */
class Sport implements \ArrayAccess
{
    /**
     * ID
     * @var int
     */
    private $id;
    /**
     * Title
     * @var string
     */
    private $title;
    /**
     * Active
     * @var bool
     */
    private $active;

    /**
     * Scoring type
     * @var int
     */
    private $scoring_type = TYPE_INDIVIDUALS;
    /**
     * Win points
     * @var null|int
     */
    private $win = null;
    /**
     * Is draw
     * @var bool
     */
    private $isDraw = false;
    /**
     * Draw points
     * @var null|int
     */
    private $draw = null;
    /**
     * Lose points
     * @var null|int
     */
    private $lose = null;
    /**
     * Forfeit points
     * @var null|int
     */
    private $forfeit = null;
    /**
     * Sets
     * @var null|int
     */
    private $sets = null;
    /**
     * Set points
     * @var null|int
     */
    private $set_points = null;
    /**
     * Match time
     * @var null|int
     */
    private $time = null;

    /**
     * Default construct
     * @param \AppBundle\Entity\Sport|null $sport
     */
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
     * Create scoring
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
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get scoring type
     *
     * @return mixed
     */
    public function getScoringType()
    {
        return $this->scoring_type;
    }

    /**
     * Set scoring type
     *
     * @param mixed $scoring_type
     */
    public function setScoringType($scoring_type)
    {
        $this->scoring_type = $scoring_type;
    }

    /**
     * Get win
     *
     * @return mixed
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * SetWin
     *
     * @param mixed $win
     */
    public function setWin($win)
    {
        $this->win = $win;
    }

    /**
     * Is draw
     *
     * @return mixed
     */
    public function getIsDraw()
    {
        return $this->isDraw;
    }

    /**
     * Set draw
     *
     * @param mixed $isDraw
     */
    public function setIsDraw($isDraw)
    {
        $this->isDraw = $isDraw;
    }

    /**
     * Get draw
     *
     * @return mixed
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * Set draw
     *
     * @param mixed $draw
     */
    public function setDraw($draw)
    {
        $this->draw = $draw;
    }

    /**
     * Get lose
     *
     * @return mixed
     */
    public function getLose()
    {
        return $this->lose;
    }

    /**
     * Set lose
     *
     * @param mixed $lose
     */
    public function setLose($lose)
    {
        $this->lose = $lose;
    }

    /**
     * Get forfeit
     *
     * @return mixed
     */
    public function getForfeit()
    {
        return $this->forfeit;
    }

    /**
     * Set forfeit
     *
     * @param mixed $forfeit
     */
    public function setForfeit($forfeit)
    {
        $this->forfeit = $forfeit;
    }

    /**
     * Get sets
     *
     * @return mixed
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * Set sets
     *
     * @param mixed $sets
     */
    public function setSets($sets)
    {
        $this->sets = $sets;
    }

    /**
     * Get set points
     *
     * @return mixed
     */
    public function getSetPoints()
    {
        return $this->set_points;
    }

    /**
     * Set set points
     *
     * @param mixed $set_points
     */
    public function setSetPoints($set_points)
    {
        $this->set_points = $set_points;
    }

    /**
     * Get time
     *
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set time
     *
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

    /**
     * Init attributes
     * @param \AppBundle\Entity\Sport $sport
     */
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