<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * License: Apache 2.0
 * Created at: 2016-01-30 02:06 PM
 */

namespace App\APDApp\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\URL;

class WBUrl
{
    /**
     * get url id e.g. my-url-1 returns 1 {id}
     *
     * @param $string
     * @param string $delimiter
     * @return int
     */
    public static function urlId($string, $delimiter = '-')
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

    /**
     * Create user profile URL
     *
     * @param $id
     * @return string
     */
    public static function profileUrl($id)
    {
        $user = User::find($id);

        if ($user) {
            return url('profile/' . self::urlTitle($user->username));
        }

        return null;
    }

    /**
     * Create url from string e.g my url to this => my-url-to-this
     *
     * @param $str
     * @param $separator
     * @param $lowercase
     * @return string
     */
    public static function urlTitle($str, $separator = '-', $lowercase = true)
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

    /**
     * Is current url active
     *
     * @param null $url
     * @return string
     */
    public static function activeUrl($url = null)
    {
        return ((URL::current() == url('/') . '/' . $url) || (URL::current() == url('/') && $url == null)) ? 'active' : '';
    }

    /**
     * For dev purposes only (url)
     *
     * @return integer
     */
    public static function randomURLExtension()
    {
        try {
            if (!request()) {
                return null;
            }

            $css_version = app_settings('css_version')->value;
            if (request()->session()->has('css_version') && env('APP_ENV') != 'local') {
                if (request()->session()->get('css_version') != $css_version) {
                    $version = rand(10, 100) . time();
                    $css_version = '?' . $version;

                    // store the current css version
                    request()->session()->put('css_version', $version);
                }
            }

            return (env('APP_ENV') == 'local') ? '?' . rand(10, 100) . time() : '?' . $css_version;
        } catch (\Exception $e) {
            error_logger($e->getMessage());
            return null;
        }
    }
}