<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */

if (!function_exists('wb_messages')) {
    /**
     * List of custom messages
     *
     * @param null $index
     * @return array|mixed
     */
    function wb_messages($index = null)
    {
        $_wb_messages = [
            'MSG_ACCESS' => 'Access is denied due to invalid credentials.'
        ];

        if ($index) {
            if (in_array($index, $_wb_messages)) {
                return $_wb_messages[$index];
            } else {
                return null;
            }
        }

        return $_wb_messages;
    }
}