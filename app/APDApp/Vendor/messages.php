<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

if (!function_exists('exception_messages')) {
    /**
     * List of custom messages
     *
     * @param null $index
     * @return array|mixed
     */
    function exception_messages($index = null)
    {
        $_messages = [
            'UNKNOWN' => 'Unknown error occurred or invalid response.',
            'PAGE_NOT_FOUND' => 'Page not found.',

            'MSG_ACCESS' => 'Access is denied due to invalid credentials.'
        ];

        if ($index) {
            if (isset($_messages[$index])) {
                return $_messages[$index];
            } else {
                return null;
            }
        }

        return $_messages;
    }
}