<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

/*
 * --------------------------------------------------------------------------
 * Random DATA for DEMO use ONLY
 * --------------------------------------------------------------------------
 */

if (!function_exists('random_first_names')) {

    function random_first_names()
    {
        $first_names = [
            'Abigail', 'Caroline', 'Elizabeth', 'Ella', 'Jasmine', 'Jennifer', 'Julia'
        ];

        $k = array_rand($first_names);
        return $first_names[$k];
    }
}

if (!function_exists('random_last_names')) {

    function random_last_names()
    {
        $last_names = [
            'Abraham', 'Ball', 'Dowd', 'Hamilton', 'Hardacre', 'Johnston', 'McDonald'
        ];

        $k = array_rand($last_names);
        return $last_names[$k];
    }
}

if (!function_exists('random_middle_names')) {

    function random_middle_names()
    {
        $last_names = [
            'Abraham', 'Cornish', 'Dowd', 'Hamilton', 'Hardacre', 'Johnston', 'McDonald'
        ];

        $k = array_rand($last_names);
        return $last_names[$k];
    }
}