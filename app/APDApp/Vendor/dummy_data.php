<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
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
            'Abigail', 'Caroline', 'Dorothy', 'Elizabeth', 'Ella', 'Jasmine', 'Jennifer', 'Julia'
        ];

        $k = array_rand($first_names);
        return $first_names[$k];
    }
}

if (!function_exists('random_last_names')) {

    function random_last_names()
    {
        $last_names = [
            'Abraham', 'Ball', 'Cornish', 'Dowd', 'Hamilton', 'Hardacre', 'Johnston', 'McDonald'
        ];

        $k = array_rand($last_names);
        return $last_names[$k];
    }
}

if (!function_exists('random_middle_names')) {

    function random_middle_names()
    {
        $last_names = [
            'Abraham', 'Ball', 'Cornish', 'Dowd', 'Hamilton', 'Hardacre', 'Johnston', 'McDonald'
        ];

        $k = array_rand($last_names);
        return $last_names[$k];
    }
}