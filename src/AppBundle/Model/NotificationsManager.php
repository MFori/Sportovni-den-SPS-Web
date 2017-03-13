<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 31.12.2016
 * Time: 16:22
 */

namespace AppBundle\Model;

use AppBundle\Entity\Notification;

class NotificationsManager
{
    private static $FCM_KEY = 'AAAA9hs2H8Y:APA91bEnwdV4jK3zo2fzROKRDB3raLBOL-magjVkpLlVo16wGbW5MudHn2H7WkWChOsMf_E_knstBKXal8deLKtC_cRjABt5s9Z01YJHNhH2Mywty5XXMd_qSyDihi2P00yG3YxVECgd';
    private static $FCM_URL = 'https://fcm.googleapis.com/fcm/send';

    private function __construct(){}

    public static function send(Notification $notification)
    {
        $fields = array(
            'to' => '/topics/' . $notification->getAddressee()->getTopic(),
            'data' => $notification->restSerialize(),
            'priority' => 'high',
            //'time_to_live' => $ttl
        );
        $headers = array(
            'Authorization:key = ' . self::$FCM_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$FCM_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            return false;
        }
        curl_close($ch);

        return true;
    }
}