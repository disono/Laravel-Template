<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
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