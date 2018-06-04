<?php
/**
 * @author          Archie, Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (webmons.com), 2016-2018
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

if (!function_exists('exceptionMessages')) {
    /**
     * Error messages
     *
     * @param null $key
     * @return array|mixed
     */
    function exceptionMessages($key = null)
    {
        $_exceptionsMessages = [
            'UNKNOWN' => 'Unknown error occurred or invalid response.',
            'AUTH_DENIED_ACCESS' => 'You are not authorize to access this route.',

            'PAGE_NOT_FOUND' => '404 Page not found.',
            'METHOD_NOT_ALLOWED' => '402 HTTP Method not allowed.',
            'BAD_REQUEST' => '400 Bad Request, can not process your request or token expired.',
        ];

        if ($key) {
            if (isset($_exceptionsMessages[$key])) {
                return $_exceptionsMessages[$key];
            }

            return $_exceptionsMessages['UNKNOWN'];
        }

        return $_exceptionsMessages;
    }
}