<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\APDApp\Helpers;

class WBMessaging
{
    /**
     * Send SMS message
     * @url https://www.itexmo.com/Developers/apidocs.php
     * @url https://telerivet.com (alternatives)
     *
     * @param $number
     * @param $message
     * @return bool
     */
    public static function SMS($number, $message)
    {
        $url = 'https://www.itexmo.com/php_api/api.php';
        $CONFIG = array('1' => $number, '2' => $message, '3' => env('SMS_API_KEY'));
        $param = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($CONFIG),
            ),
        );
        $context = stream_context_create($param);

        return file_get_contents($url, false, $context);
    }
}