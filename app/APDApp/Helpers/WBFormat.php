<?php
/**
 * @author Archie, Disono (webmonsph@gmail.com)
 * @git https://github.com/disono/Laravel-Template
 * @copyright Webmons Development Studio. (webmons.com), 2016-2017
 * @license Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\APDApp\Helpers;

class WBFormat
{
    /**
     * Money formatted
     *
     * @param $number
     * @param string $sign
     * @return string
     */
    public static function money($number, $sign = '&#8369;')
    {
        return $sign . ' ' . number_format($number, 2);
    }
}