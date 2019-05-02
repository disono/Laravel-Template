<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
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
            'RAW' => '',
            'INVALID_RAW' => 'Invalid',
            'UNKNOWN' => 'Unknown error occurred or invalid response.',
            'AUTH_DENIED_ACCESS' => 'You are not authorize to access this route.',
            'AUTH_FORBIDDEN_ACCESS' => 'You are not allowed to access this route (FORBIDDEN).',

            'PAGE_NOT_FOUND' => '404 Page not found.',
            'METHOD_NOT_ALLOWED' => '402 HTTP Method not allowed.',
            'BAD_REQUEST' => '400 Bad Request, can not process your request or token expired.',

            'TOKEN_NOT_FOUND' => 'Token or code is not found.',
            'TOKEN_IS_EXPIRED' => 'Token is expired please resend another verification token. To resend another token please login.',

            'USER_NOT_FOUND' => 'User is not found, profile not exists.',

            'DB_INVALID_TABLE' => 'Invalid table name.'
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

if (!function_exists('throwError')) {
    /**
     * Throw exception with messages
     *
     * @param null $index
     * @param null $custom_message
     * @throws Exception
     */
    function throwError($index = null, $custom_message = null)
    {
        $message = $custom_message;
        if ($index) {
            $custom_message = ($custom_message) ? ' ' . $custom_message : '';
            $message = exceptionMessages($index) . $custom_message;
        }

        throw new \Exception($message);
    }
}