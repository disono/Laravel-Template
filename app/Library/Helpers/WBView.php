<?php
/**
 * Author: Archie, Disono (disono.apd@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
 */
namespace App\Library\Helpers;

use GrahamCampbell\HTMLMin\Facades\HTMLMin;
use Jenssegers\Agent\Agent;

class WBView
{
    /**
     * Theme
     *
     * @param null $file
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function theme($file = null, $data = [])
    {
        $current_them = self::current_theme();

        if (env('APP_ENV') === 'local') {
            return view($current_them . trim($file, '/'), $data);
        }

        return HTMLMin::blade(view($current_them . trim($file, '/'), $data));
    }

    /**
     * Check for current theme
     *
     * @return string
     */
    public static function current_theme()
    {
        $agent = new Agent();

        return 'theme.main.' .
        ((($agent->isMobile() === 1 || $agent->isTablet() === 1) && env('VIEW_MOBILE') === true) ? 'mobile.' : 'desktop.');
    }

    /**
     * Admin view
     *
     * @param null $file
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function admin_view($file, $data)
    {
        if (env('APP_ENV') === 'local') {
            return view('admin.' . $file, $data);
        }

        return HTMLMin::blade(view('admin.' . $file, $data));
    }
}