<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

use App\Models\User;

if (!function_exists('urlId')) {
    /**
     * Get url id e.g. my-url-1 returns 1 {id}
     *
     * @param $string
     * @param string $delimiter
     * @return int
     */
    function urlId($string, $delimiter = '-')
    {
        if (!in_array($delimiter, array('-', '_'))) {
            $delimiter = '-';
        }

        $urlString = explode($delimiter, $string);
        if (!is_array($urlString)) {
            return 0;
        }

        if (!is_integer((int)end($urlString))) {
            return 0;
        }

        return (int)end($urlString);
    }
}

if (!function_exists('profileUrl')) {
    /**
     * Create user profile URL
     *
     * @param $id
     * @return string
     */
    function profileUrl($id)
    {
        $user = (new User())->single($id);

        if ($user) {
            return url('u/' . urlTitle($user->username));
        }

        return null;
    }
}

if (!function_exists('urlTitle')) {
    /**
     * Create url from string e.g my url to this => my-url-to-this
     *
     * @param $str
     * @param $separator
     * @param $lowercase
     * @return string
     */
    function urlTitle($str, $separator = '-', $lowercase = true)
    {
        if ($separator == 'dash') {
            $separator = '-';
        } else if ($separator == 'underscore') {
            $separator = '_';
        }

        $q_separator = preg_quote($separator);

        $trans = array(
            '&.+?;' => '',
            '[^a-z0-9 _-]' => '',
            '\s+' => $separator,
            '(' . $q_separator . ')+' => $separator
        );

        $str = strip_tags($str);
        foreach ($trans as $key => $val) {
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }

        if ($lowercase === true) {
            $str = strtolower($str);
        }

        return trim($str, $separator);
    }
}