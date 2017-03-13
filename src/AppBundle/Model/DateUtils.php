<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 19.12.2016
 * Time: 20:44
 */

namespace AppBundle\Model;

class DateUtils
{
    private static $months = array(
        '01' => 'Leden',
        '02' => 'Únor',
        '03' => 'Březen',
        '04' => 'Duben',
        '05' => 'Květen',
        '06' => 'Červen',
        '07' => 'Červenec',
        '08' => 'Srpen',
        '09' => 'Září',
        '10' => 'Říjen',
        '11' => 'Listopad',
        '12' => 'Prosinec',
    );

    public static function createTournamentName()
    {
        return self::$months[date('m')] . ' ' . date('Y');
    }
}