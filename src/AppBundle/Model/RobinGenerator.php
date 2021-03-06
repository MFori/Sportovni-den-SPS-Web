<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 21.12.2016
 * Time: 19:06
 */

namespace AppBundle\Model;

/**
 * Class RobinGenerator
 * @package AppBundle\Model
 */
class RobinGenerator extends MatchesGenerator
{
    /**
     * Generate matches
     *
     * @return array
     */
    public function generate()
    {
        return $this->createRobinMatches($this->teams, array(
            'group' => null,
            'status' => $this->defaultStatus
        ));
    }
}